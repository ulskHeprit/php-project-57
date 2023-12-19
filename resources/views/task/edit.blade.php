<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('task.Edit task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{ html()->modelForm($task, 'PATCH', route('tasks.update', $task))->open() }}
                {{ html()->label(__('Name'), 'name') }}
                {{ html()->text('name') }}
                {{ html()->label(__('Description'), 'description') }}
                {{ html()->textarea('description') }}
                {{ html()->label(__('Status'), 'status_id') }}
                {{ html()->text('status_id') }}
                {{ html()->label(__('Assigned to'), 'assigned_to_id') }}
                {{ html()->text('assigned_to_id') }}
                {{ html()->submit(__('Update')) }}
            {{ html()->closeModelForm() }}
        </div>
    </div>
</x-app-layout>