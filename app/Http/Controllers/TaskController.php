<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // List all tasks for logged user
    public function index()
    {
        return response()->json(Task::where('user_id', Auth::id())->get());
    }

    // Store new task
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'status' => 'in:pending,in-progress,completed',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'status' => $request->status ?? 'pending',
            'user_id' => Auth::id(),
        ]);

        return response()->json($task, 201);
    }

    // Update task
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task); // optional if policies

        $request->validate([
            'title' => 'string',
            'status' => 'in:pending,in-progress,completed',
        ]);

        $task->update($request->only('title', 'status'));

        return response()->json($task);
    }

    // Delete task
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task); // optional if policies

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
