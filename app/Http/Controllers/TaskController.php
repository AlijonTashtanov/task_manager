<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Models\Task;

/**
 * @OA\Info(
 *     title="Laravel Task",
 *     version="1.0.0",
 *     description="Это API для управления задачами, построенное на Laravel."
 * )
 *
 * @OA\Tag(
 *     name="Tasks",
 *     description="Всё о задачах"
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
     *     summary="Получить все задачи",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Поиск задач по названию",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Список задач")
     * )
     */
    public function index(Request $request)
    {
        $tasks = $this->taskService->getAllTasks($request->search, $request->sort);
        return response()->json($tasks);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Получить задачу по ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID задачи",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Детали задачи")
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
     *     summary="Создать новую задачу",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "due_date", "priority", "status", "category"},
     *             @OA\Property(property="title", type="string", example="Изучить Laravel"),
     *             @OA\Property(property="description", type="string", example="Завершить учебник по API Laravel"),
     *             @OA\Property(property="due_date", type="string", format="date-time", example="2025-01-20T15:00:00"),
     *             @OA\Property(property="priority", type="string", enum={"низкий", "средний", "высокий"}, example="высокий"),
     *             @OA\Property(property="status", type="string", enum={"в ожидании", "выполнено"}, example="в ожидании"),
     *             @OA\Property(property="category", type="string", example="Работа")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Задача успешно создана")
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
     *     summary="Обновить существующую задачу",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID задачи",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Обновленная задача"),
     *             @OA\Property(property="status", type="string", enum={"в ожидании", "выполнено"}, example="выполнено")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Задача успешно обновлена")
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
     *     summary="Удалить задачу",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID задачи",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Задача успешно удалена")
     * )
     */
    public function destroy($id)
    {
        $this->taskService->deleteTask($id);
        return response()->json(['message' => 'Task deleted successfully']);
    }
}