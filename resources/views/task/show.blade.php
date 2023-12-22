<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('task.Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <p>{{ __('c.ID') }}: {{ $task->id }}</p>
            <p>{{ __('c.Name') }}: {{ $task->name }}</p>
            <p>{{ __('c.Description') }}: {{ $task->description }}</p>
            <p>{{ __('c.Status') }}: {{ $task->status->name }}</p>
            <p>{{ __('c.Creation date') }}: {{ $task->created_at }}</p>
            @if (Auth::user()?->id === $task->creator->id)
                <p>
                    <a href="{{ route('tasks.edit', $task) }}">{{ __('c.Edit') }}</a>
                    {{ html()->form('DELETE', route('tasks.destroy', $task))->open() }}
                        {{ html()->submit(__('c.Delete')) }}
                    {{ html()->form()->close() }}
                </p>
            @endif
            <p>
                @foreach($task->labels as $label)
                    {{$label->name}}<br>
                @endforeach
            </p>
        </div>
    </div>
</x-app-layout>
