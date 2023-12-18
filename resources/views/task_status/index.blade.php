<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('task_status.Task statuses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <a href="{{ route('task_statuses.create') }}">{{ __('task_status.Create task status') }}</a>
            <table class="mt-4">
                <thead class="border-b-2 border-solid border-black text-left">
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Creation date') }}</th>
                        @if (Auth::user())
                        <th>{{ __('Action') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @foreach($taskStatuses as $taskStatus)
                    <tr class="border-b border-dashed text-left">
                        <td>{{ $taskStatus->id }}</td>
                        <td>{{ $taskStatus->name }}</td>
                        <td>{{ $taskStatus->created_at }}</td>
                        @if (Auth::user())
                            <td>
                                <a href="{{ route('task_statuses.edit', $taskStatus) }}">{{ __('Edit') }}</a>
                                {{ html()->form('DELETE', route('task_statuses.destroy', $taskStatus))->open() }}
                                    {{ html()->submit('Delete') }}
                                {{ html()->form()->close() }}
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
