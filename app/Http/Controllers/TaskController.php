<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Models\Task;

/**
 * @OA\Info(
 *     title="Laravel Task API",
 *     version="1.0.0",
 *     description="This is a Task Management API built with Laravel."
 * )
 *
 * @OA\Tag(
 *     name="Tasks",
 *     description="Everything about tasks"
 * )
 */
class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Get all tasks",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search tasks by title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="List of tasks")
     * )
     */
    public function index(Request $request)
    {
        // dd("works");
        $tasks = $this->taskService->getAllTasks($request->search, $request->sort);
        return response()->json($tasks);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Get a single task by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Task ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Task details")
     * )
     */
    public function show($id)
    {
        $task = $this->taskService->getTaskById($id);
        return response()->json($task);
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Create a new task",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "due_date", "priority", "status", "category"},
     *             @OA\Property(property="title", type="string", example="Learn Laravel"),
     *             @OA\Property(property="description", type="string", example="Complete Laravel API tutorial"),
     *             @OA\Property(property="due_date", type="string", format="date-time", example="2025-01-20T15:00:00"),
     *             @OA\Property(property="priority", type="string", enum={"low", "medium", "high"}, example="high"),
     *             @OA\Property(property="status", type="string", enum={"pending", "completed"}, example="pending"),
     *             @OA\Property(property="category", type="string", example="Work")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Task created successfully")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|string|max:100',
            'status' => 'required|in:pending,completed',
        ]);

        $task = $this->taskService->createTask($validated);
        return response()->json(['id' => $task->id, 'message' => 'Task created successfully'], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Update an existing task",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Task ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Task"),
     *             @OA\Property(property="status", type="string", enum={"pending", "completed"}, example="completed")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Task updated successfully")
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'date',
            'priority' => 'in:low,medium,high',
            'category' => 'string|max:100',
            'status' => 'in:pending,completed',
        ]);

        $task = $this->taskService->updateTask($id, $validated);
        return response()->json(['message' => 'Task updated successfully']);
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Delete a task",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Task ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Task deleted successfully")
     * )
     */
    public function destroy($id)
    {
        $this->taskService->deleteTask($id);
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
