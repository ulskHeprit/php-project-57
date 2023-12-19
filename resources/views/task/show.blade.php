<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('task.Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <p>{{ __('ID') }}: {{ $task->id }}</p>
            <p>{{ __('Name') }}: {{ $task->name }}</p>
            <p>{{ __('Description') }}: {{ $task->description }}</p>
            <p>{{ __('Status') }}: {{ $task->status->name }}</p>
            <p>{{ __('Creation date') }}: {{ $task->created_at }}</p>
            @if (Auth::user()?->id === $task->creator->id)
                <p>
                    <a href="{{ route('tasks.edit', $task) }}">{{ __('Edit') }}</a>
                    {{ html()->form('DELETE', route('tasks.destroy', $task))->open() }}
                        {{ html()->submit('Delete') }}
                    {{ html()->form()->close() }}
                </p>
            @endif
        </div>
    </div>
</x-app-layout>