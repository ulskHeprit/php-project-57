<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('task_status.Create task status') }}
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
            {{ html()->modelForm($taskStatus, 'POST', route('task_statuses.store'))->open() }}
                {{ html()->label(__('c.Name'), 'name') }}
                {{ html()->text('name') }}
                {{ html()->submit(__('c.Create')) }}
            {{ html()->closeModelForm() }}
        </div>
    </div>
</x-app-layout>
