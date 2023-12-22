<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('task_status.Task statuses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <p>{{ __('c.ID') }}: {{ $taskStatus->id }}</p>
            <p>{{ __('c.Name') }}: {{ $taskStatus->name }}</p>
            <p>{{ __('c.Creation date') }}: {{ $taskStatus->created_at }}</p>
            @if (Auth::user())
                <p>
                    <a href="{{ route('task_statuses.edit', $taskStatus) }}">{{ __('c.Edit') }}</a>
                    {{ html()->form('DELETE', route('task_statuses.destroy', $taskStatus))->open() }}
                        {{ html()->submit(__('c.Delete')) }}
                    {{ html()->form()->close() }}
                </p>
            @endif
        </div>
    </div>
</x-app-layout>
