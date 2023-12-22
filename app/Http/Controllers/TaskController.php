<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $query = Task::query();

        if (!empty($filter['status_id'])) {
            $query->where('status_id', $filter['status_id']);
        }
        if (!empty($filter['created_by_id'])) {
            $query->where('created_by_id', $filter['created_by_id']);
        }
        if (!empty($filter['assigned_to_id'])) {
            $query->where('assigned_to_id', $filter['assigned_to_id']);
        }

        $tasks = $query->paginate(20);

        return view('task.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $task = new Task();

        return view('task.create', compact('task'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|min:1',
            'description' => 'nullable|max:255',
            'status_id' => 'required|exists:App\Models\TaskStatus,id',
            'assigned_to_id' => 'nullable|exists:App\Models\User,id',
        ]);

        $status_id = $data['status_id'];
        $assigned_to_id = $data['assigned_to_id'] ?? null;
        unset($data['status_id'], $data['assigned_to_id']);
        $data['created_by_id'] = auth()->id();

        $task = new Task();
        $task->fill($data);
        $task->status_id = $status_id;
        $task->created_by_id = auth()->user()->id;
        if ($assigned_to_id) {
            $task->assigned_to_id = $assigned_to_id;
        }
        $task->save();

        return redirect()
            ->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('task.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $data = $this->validate($request, [
            'name' => 'required|min:1',
            'description' => 'nullable|max:255',
            'status_id' => 'required|exists:App\Models\TaskStatus,id',
            'assigned_to_id' => 'nullable|exists:App\Models\User,id',
        ]);

        $status_id = $data['status_id'] ?? null;
        $assigned_to_id = $data['assigned_to_id'] ?? null;
        unset($data['status_id'], $data['assigned_to_id']);
        $data['created_by_id'] = auth()->id();

        $task->fill($data);
        if ($status_id) {
            $task->status_id = $status_id;
        }
        if ($assigned_to_id) {
            $task->assigned_to_id = $assigned_to_id;
        }
        $task->save();

        return redirect()
            ->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (Auth::user()->id !== $task->creator->id) {
            return response('Unauthenticated.', 401);
        }
        $task->delete();

        return redirect()->route('tasks.index');
    }
}
