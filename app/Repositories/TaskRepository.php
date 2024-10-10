<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{

    public function all(array $filters)
    {
        $query = Task::query();

        if (!empty($filters['status'])) {
            $query->status($filters['status']);
        }

        if (!empty($filters['due_date'])) {
            $query->whereDate('due_date', $filters['due_date']);
        }

        if (!empty($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        return $query->orderBy('due_date')->paginate(10, ['*'], 'page', $filters['page'] ?? null);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): Task
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task;
    }

    public function delete(int $id): void
    {
        $task = Task::find($id);
        if (!$task) {
            throw new \Exception('Task not found', 404);
        }
        $task->delete();
    }
}
