<?php

namespace App\Http\Controllers\Api;

use App\Data\TablesFilters\TeamFilterData;
use App\Data\TeamData;
use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamFormRequest;
use App\Http\Requests\TeamsListRequest;
use App\Http\Requests\TeamUsersFormRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Repositories\TeamRepository;
use App\Services\TeamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends Controller
{
    /**
     * The team repository instance.
     *
     * @var TeamRepository
     */
    protected TeamRepository $repository;

    /**
     * The team repository instance.
     *
     * @var TeamService
     */
    protected TeamService $service;

    /**
     * @param Team $team
     */
    public function __construct(Team $team)
    {
        $this->middleware('auth:sanctum');

        $this->repository = $team->repository();
        $this->service = $team->service();
    }

    /**
     * Check policy
     *
     * @param string $method
     * @return bool
     */
    private function checkPolicy(string $method): bool
    {
        return Gate::inspect($method, new Team())->allowed();
    }

    /**
     * Display a listing of the resource.
     *
     * @param TeamsListRequest $request
     */
    public function index(TeamsListRequest $request): JsonResponse
    {
        if (!$this->checkPolicy('viewAny')) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        $filters = TeamFilterData::from($request->validated());
        $filters = $filters->except('page', 'per_page', 'direction');

        $teams = $this->repository->getTeamsListWithFilters($filters->toCollect())
            ->orderBy('id', $filters->direction)
            ->paginate($filters->per_page, ['*'], 'page', $filters->page);

        return $this->sendResponse('success', 'Teams list.', Response::HTTP_OK, [
            'list' => TeamResource::collection($teams),
            'pagination' => [
                'current_page' => $teams->currentPage(),
                'per_page' => $teams->perPage(),
                'total' => $teams->total(),
                'last_page' => $teams->lastPage(),
                'next_page_url' => $teams->nextPageUrl(),
                'prev_page_url' => $teams->previousPageUrl(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TeamFormRequest $request
     */
    public function store(TeamFormRequest $request)
    {
        if (!$this->checkPolicy('create')) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        try {
            $team = $this->service->create($request->validated());

            if (!$team->id) throw new \Exception('Exception Error! Team not created !');

            return $this->sendResponse('success', 'Team - [ ' . $team->id . ' ] successfully created !', Response::HTTP_OK, [
                'item' => TeamResource::make($team)
            ]);

        } catch (\Exception $exception) {
            return $this->sendResponse('error', $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        if (!$this->checkPolicy('view')) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        return $this->sendResponse('success', 'Team details.', Response::HTTP_OK, [
            'item' => TeamResource::make($team)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamFormRequest $request, Team $team)
    {
        if (!$this->checkPolicy('update')) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        $data = TeamData::from($request->validated());

        try {
            $team->service()->update($data->all());

            return $this->sendResponse('success', 'Team - [ ' . $team->id . ' ] successfully updated !', Response::HTTP_OK, [
                'item' => TeamResource::make($team)
            ]);

        } catch (\Exception $exception) {
            return $this->sendResponse('error', $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Team $team
     */
    public function destroy(Team $team)
    {
        if (!$this->checkPolicy('delete')) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        try {
            $team->tasks?->each(function ($task) {
                $task->update([
                    'status' => TaskStatus::PENDING(),
                    'team_id' => null,
                    'user_id' => null,
                ]);
            });

            $team->delete();

            return $this->sendResponse('success', 'Team successfully deleted.', Response::HTTP_OK);

        } catch (\Exception $exception) {
            return $this->sendResponse('error', $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TeamUsersFormRequest $request
     * @param Team $team
     * @return JsonResponse
     */
    public function syncUsers(TeamUsersFormRequest $request, Team $team)
    {
        if (!$this->checkPolicy('updateTeamUsers')) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        $users = $request->validated();

        try {
            $team->users()->sync($users['team']);

            return $this->sendResponse('success', 'Team composition has been successfully updated.', Response::HTTP_OK, [
                'item' => TeamResource::make($team)
            ]);

        } catch (\Exception $exception) {
            return $this->sendResponse('error', $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Team $team
     * @param int|string $userId
     * @return JsonResponse
     */
    public function removeUser(Team $team, int|string $userId)
    {
        if (!$this->checkPolicy('updateTeamUsers')) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        try {
            $team->users()->detach($userId);

            return $this->sendResponse('success', 'Team composition has been successfully updated.', Response::HTTP_OK);

        } catch (\Exception $exception) {
            return $this->sendResponse('error', $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
