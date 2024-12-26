<?php

namespace App\Http\Controllers\Api;

use App\Data\CommentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentFormRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Check policy
     *
     * @param string $method
     * @return bool
     */
    private function checkPolicy(string $method, Task $task): bool
    {
        return Gate::inspect($method, $task)->allowed();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Task $task)
    {
        if (!$this->checkPolicy('showComments', $task)) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        return $this->sendResponse('success', 'Task comments list.', Response::HTTP_OK, [
            'comments' => CommentResource::collection($task->comments),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentFormRequest $request, Task $task)
    {
        if (!$this->checkPolicy('addComments', $task)) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        $data = CommentData::from(array_merge(
            ['user_id' => auth()->user()->id],
            $request->validated()
        ));

        try {
            $comment = $task->comments()->create($data->all());

            if (!$comment->id) throw new \Exception('Exception Error! Comment not created !');

            return $this->sendResponse('success', 'Comment - [ ' . $comment->id . ' ] successfully created !', Response::HTTP_OK);

        } catch (\Exception $exception) {
            return $this->sendResponse('error', $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (!Gate::inspect('delete', $comment)->allowed()) {
            return $this->sendResponse('forbidden', 'Forbidden!', Response::HTTP_FORBIDDEN);
        }

        $comment->delete();

        return $this->sendResponse('success', 'Comment successfully deleted.', Response::HTTP_OK);
    }
}
