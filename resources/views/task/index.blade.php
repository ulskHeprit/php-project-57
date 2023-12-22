<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('task.Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (Auth::user())
                <a href="{{ route('tasks.create') }}">{{ __('task.Create task') }}</a>
            @endif
            {{ html()->form('GET', route('tasks.index'))->open() }}
                {{ html()->label(__('task.Status'), 'filter[status_id]') }}
                {{ html()->text('filter[status_id]', request()->filter['status_id'] ?? '') }}
                {{ html()->label(__('task.Created by'), 'filter[created_by_id]') }}
                {{ html()->text('filter[created_by_id]', request()->filter['created_by_id'] ?? '')}}
                {{ html()->label(__('task.Assigned to'), 'filter[assigned_to_id]') }}
                {{ html()->text('filter[assigned_to_id]', request()->filter['assigned_to_id'] ?? '')}}
                {{ html()->submit(__('c.Submit')) }}
            {{ html()->form()->close() }}
            <table class="mt-4">
                <thead class="border-b-2 border-solid border-black text-left">
                    <tr>
                        <th>{{ __('c.ID') }}</th>
                        <th>{{ __('c.Name') }}</th>
                        <th>{{ __('c.Status') }}</th>
                        <th>{{ __('c.Creator') }}</th>
                        <th>{{ __('c.Assigned to') }}</th>
                        <th>{{ __('c.Creation date') }}</th>
                        @if (Auth::user())
                        <th>{{ __('c.Action') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @foreach($tasks as $task)
                    <tr class="border-b border-dashed text-left">
                        <td>{{ $task->id }}</td>
                        <td><a href="{{ route('tasks.show', $task) }}">{{ $task->name }}</a></td>
                        <td>{{ $task->status->name }}</td>
                        <td>{{ $task->creator->name }}</td>
                        <td>{{ $task->assignedUser?->name }}</td>
                        <td>{{ $task->created_at }}</td>
                        @if (Auth::user())
                            <td>
                                <a href="{{ route('tasks.edit', $task) }}">{{ __('c.Edit') }}</a>
                                @if (Auth::user()->id === $task->creator->id)
                                    {{ html()->form('DELETE', route('tasks.destroy', $task))->open() }}
                                        {{ html()->submit(__('c.Delete')) }}
                                    {{ html()->form()->close() }}
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
