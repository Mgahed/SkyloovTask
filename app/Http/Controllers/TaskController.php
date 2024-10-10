<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }


    // List all tasks with optional filters
    public function index(Request $request)
    {
        try {
            $tasks = $this->taskRepository->all($request->only(['status', 'due_date', 'title']));
            $taskResource = TaskResource::collection($tasks);
            return customResponse($taskResource);
        } catch (\Exception $e) {
            return customResponse($e->getMessage(), 500);
        }
    }

    // Store a new task
    public function store(TaskRequest $request)
    {
        try {
            $task = $this->taskRepository->create($request->validated());
            $taskResource = new TaskResource($task);
            return customResponse($taskResource, 201);
        } catch (\Exception $e) {
            return customResponse($e->getMessage(), 500);
        }
    }

    // Update an existing task
    public function update(TaskRequest $request)
    {
        try {
            $task = $this->taskRepository->update($request->id, $request->validated());
            $taskResource = new TaskResource($task);
            return customResponse($taskResource);
        } catch (\Exception $e) {
            return customResponse($e->getMessage(), 500);
        }
    }

    // Delete a task
    public function destroy(Request $request)
    {
        try {
            $this->taskRepository->delete($request->id);
            return customResponse('Task deleted successfully', 200);
        } catch (\Exception $e) {
            return customResponse($e->getMessage(), $e->getCode());
        }
    }
}
