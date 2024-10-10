<?php

namespace App\Repositories;

use App\Models\Task;

interface TaskRepositoryInterface
{
    public function all(array $filters);
    public function create(array $data): Task;
    public function update(int $id, array $data): Task;
    public function delete(int $id): void;
}
