<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taskStatuses = TaskStatus::paginate(10);

        return view('task_status.index', compact('taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $taskStatus = new TaskStatus();

        return view('task_status.create', compact('taskStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|min:1',
        ]);

        $article = new TaskStatus();
        $article->fill($data);
        $article->save();

        return redirect()
            ->route('task_statuses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskStatus $taskStatus)
    {
        return view('task_status.show', compact('taskStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskStatus $taskStatus)
    {
        return view('task_status.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskStatus $taskStatus)
    {
        $data = $this->validate($request, [
            'name' => 'required'
        ]);

        $taskStatus->fill($data);
        $taskStatus->save();

        return redirect()
            ->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskStatus $taskStatus)
    {
        $taskStatus->delete();

        return redirect()->route('task_statuses.index');
    }
}
