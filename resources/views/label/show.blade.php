<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('label.Label') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <p>{{ __('c.ID') }}: {{ $label->id }}</p>
            <p>{{ __('c.Name') }}: {{ $label->name }}</p>
            <p>{{ __('c.Description') }}: {{ $label->description }}</p>
            <p>{{ __('c.Creation date') }}: {{ $label->created_at }}</p>
            @if (Auth::user())
                <p>
                    <a href="{{ route('labels.edit', $label) }}">{{ __('c.Edit') }}</a>
                    {{ html()->form('DELETE', route('labels.destroy', $label))->open() }}
                        {{ html()->submit(__('c.Delete')) }}
                    {{ html()->form()->close() }}
                </p>
            @endif
        </div>
    </div>
</x-app-layout>
