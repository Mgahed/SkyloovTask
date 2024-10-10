<?php

namespace Tests\Feature;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    // Helper function to create tasks for testing
    private function createTask($attributes = [])
    {
        return Task::factory()->create($attributes);
    }

    // header
    private $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    ############################
    ##Test for Creating a Task##
    ############################

    public function test_can_create_task()
    {
        $data = [
            'title' => 'Task 1',
            'description' => 'Task 1 description',
            'status' => 'pending',
            'due_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/tasks', $data, $this->headers);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'title' => 'Task 1',
                'status' => 'pending',
            ]);
    }

    public function test_create_task_fails_with_invalid_data()
    {
        $data = [
            'title' => '', // title is required
            'description' => 'Task without title',
            'status' => 'pending',
            'due_date' => Carbon::now()->subDay()->format('Y-m-d'), // due date cannot be in the past
        ];

        $response = $this->postJson('/api/tasks', $data, $this->headers);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'due_date']);
    }

    #############################
    ##Test for Retrieving Tasks##
    #############################

    public function test_can_get_all_tasks()
    {
        $task1 = $this->createTask(['title' => 'Task 1', 'due_date' => Carbon::now()->addDays(1)]);
        $task2 = $this->createTask(['title' => 'Task 2', 'due_date' => Carbon::now()->addDays(2)]);

        $response = $this->getJson('/api/tasks', $this->headers);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Task 1'])
            ->assertJsonFragment(['title' => 'Task 2']);
    }

    public function test_can_filter_tasks_by_status()
    {
        $task1 = $this->createTask(['status' => 'pending']);
        $task2 = $this->createTask(['status' => 'completed']);

        $response = $this->getJson('/api/tasks?status=pending', $this->headers);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'pending'])
            ->assertJsonMissing(['status' => 'completed']);
    }

    public function test_can_filter_tasks_by_due_date()
    {
        $task1 = $this->createTask(['due_date' => Carbon::now()->addDays(1)]);
        $task2 = $this->createTask(['due_date' => Carbon::now()->addDays(2)]);

        $response = $this->getJson('/api/tasks?due_date=' . Carbon::now()->addDays(1)->format('Y-m-d'), $this->headers);

        $response->assertStatus(200)
            ->assertJsonFragment(['due_date' => Carbon::now()->addDays(1)->format('Y-m-d')])
            ->assertJsonMissing(['due_date' => Carbon::now()->addDays(2)->format('Y-m-d')]);
    }

    ############################
    ##Test for Updating a Task##
    ############################

    public function test_can_update_task()
    {
        $task = $this->createTask([
            'title' => 'Old Title',
            'description' => 'Old Description',
            'status' => 'pending',
        ]);

        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => 'in_progress',
            'due_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
        ];

        $response = $this->putJson('/api/tasks?id=' . $task->id, $data, $this->headers);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Title'])
            ->assertJsonFragment(['status' => 'in_progress']);
    }

    public function test_update_task_fails_with_invalid_data()
    {
        $task = $this->createTask([
            'title' => 'Task Title',
            'description' => 'Task Description',
            'status' => 'pending',
            'due_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
        ]);

        $data = [
            'title' => '', // Title is required
            'status' => 'invalid_status', // Invalid status
        ];

        $response = $this->putJson('/api/tasks?id=' . $task->id, $data, $this->headers);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'status']);
    }


    ############################
    ##Test for Deleting a Task##
    ############################

    public function test_can_delete_task()
    {
        $task = $this->createTask();

        $response = $this->deleteJson('/api/tasks?id=' . $task->id, [], $this->headers);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Task deleted successfully']);
    }

    public function test_delete_fails_with_nonexistent_task()
    {
        $response = $this->deleteJson('/api/tasks?id=999', [], $this->headers);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Task not found']);
    }


    ##################################
    ##Test for Pagination and Search##
    ##################################

    public function test_can_paginate_tasks()
    {
        Task::factory()->count(15)->create(); // Create 15 tasks for pagination

        $response = $this->getJson('/api/tasks?page=2');

        $response->assertStatus(200);
    }

    public function test_can_search_tasks_by_title()
    {
        $task1 = $this->createTask(['title' => 'Important Task']);
        $task2 = $this->createTask(['title' => 'Not Task']);

        $response = $this->getJson('/api/tasks?title=Important');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Important Task'])
            ->assertJsonMissing(['title' => 'Not Task']);
    }


    #############################
    ##Test for Validation Rules##
    #############################

    public function test_validation_fails_for_create_task()
    {
        $data = [
            'title' => '', // Title is required
            'description' => '',
            'status' => 'invalid_status', // Invalid status value
            'due_date' => 'invalid_date', // Invalid date format
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'description', 'status', 'due_date']);
    }



}
