<?php

namespace App\Http\Controllers\Api;

use App\Data\TablesFilters\TaskFilterData;
use App\Data\TaskData;
use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskFormRequest;
use App\Http\Requests\TasksListRequest;
use App\Http\Resources\TaskListResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * The task repository instance.
     *
     * @var TaskRepository
     */
    protected TaskRepository $repository;

    /**
     * The task service instance.
     *
     * @var TaskService
     */
    protected TaskService $service;

    /**
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->middleware('auth:sanctum');

        $this->repository = $task->repository();
        $this->service = $task->service();
    }

    /**
     * Check policy
     *
     * @param mixed $task
     * @param string $method
     * @return bool
     */
    private function checkPolicy(string $method, mixed $task = null): bool
    {
        $task = $task ?? Task::class;

        return Gate::inspect($method, $task)->allowed();
    }

    /**
     * Display a listing of the resource.
     *
     * @param TasksListRequest $request
     */
    public function index(TasksListRequest $request): JsonResponse
    {
        if (!$this->checkPolicy('viewAny')) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        $user = (!auth()->user()->hasRole('manager'))? auth()->user() : null;

        $filters = TaskFilterData::from($request->validated());
        $filters = $filters->except('page', 'per_page', 'direction');

        $tasks = $this->repository->getUserTaskListWithFilters($filters->toCollect(), $user)
            ->orderBy('id', $filters->direction)
            ->paginate($filters->per_page, ['*'], 'page', $filters->page);

        return $this->sendResponse('success', 'Tasks list.', Response::HTTP_OK, [
            'list' => TaskListResource::collection($tasks),
            'filters_options' => $this->getFilterOptions($user),
            'pagination' => [
                'current_page' => $tasks->currentPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
                'last_page' => $tasks->lastPage(),
                'next_page_url' => $tasks->nextPageUrl(),
                'prev_page_url' => $tasks->previousPageUrl(),
            ]
        ]);
    }

    private function getFilterOptions(mixed $user): Collection
    {
        $filtersOptions = collect([
            'status' => TaskStatus::forSelect(),
            'team' => (new Team())->repository()->getTeamsListForSelect(),
            'user' => (new User)->repository()->getUsersList()
        ]);

        return (!$user)? $filtersOptions : $filtersOptions->only('status');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskFormRequest $request
     */
    public function store(TaskFormRequest $request)
    {
        if (!$this->checkPolicy('create')) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        $data = TaskData::from($request->validated());

        try {
            $task = $this->service->create($data->all());

            if (!$task->id) throw new \Exception('Exception Error! Task not created !');

            return $this->sendResponse('success', 'Task - [ ' . $task->id . ' ] successfully created !', Response::HTTP_OK, [
                'item' => TaskResource::make($task)
            ]);

        } catch (\Exception $exception) {
            return $this->sendResponse('error', $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if (!$this->checkPolicy('view', $task)) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        return $this->sendResponse('success', 'Task details.', Response::HTTP_OK, [
            'item' => TaskResource::make($task)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskFormRequest $request, Task $task)
    {
        if (!$this->checkPolicy('update', $task)) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        $data = TaskData::from($request->validated());

        if (!$this->checkPolicy('isManager')) {
            if (!$this->checkPolicy('update', $task)) {
                return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
            }
            $data = $data->only('status');
        }

        try {
            $task->service()->update($data->all());

            return $this->sendResponse('success', 'Task - [ ' . $task->id . ' ] successfully updated !', Response::HTTP_OK, [
                'item' => TaskResource::make($task)
            ]);

        } catch (\Exception $exception) {
            return $this->sendResponse('error', $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (!$this->checkPolicy('delete')) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        $task->delete();

        return $this->sendResponse('success', 'Task successfully deleted.', Response::HTTP_OK);
    }
}
