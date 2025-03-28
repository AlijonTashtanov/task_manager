<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskService
{
    public function getAllTasks($search = null, $sort = 'due_date', $direction = 'asc')
    {
        $query = Task::query();
    
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }
    
        $validColumns = ['id', 'title', 'due_date', 'created_at', 'updated_at'];
        $sort = in_array($sort, $validColumns) ? $sort : 'due_date';
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';
    
        return $query->orderBy($sort, $direction)->paginate(10);
    }

    public function getTaskById($id)
    {
        return Task::findOrFail($id);
    }

    public function createTask(array $data)
    {
        return Task::create($data);
    }

    public function updateTask($id, array $data)
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task;
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        return $task->delete();
    }
}
