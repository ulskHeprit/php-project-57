<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('label.Label') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <p>{{ __('ID') }}: {{ $label->id }}</p>
            <p>{{ __('Name') }}: {{ $label->name }}</p>
            <p>{{ __('Description') }}: {{ $label->description }}</p>
            <p>{{ __('Creation date') }}: {{ $label->created_at }}</p>
            @if (Auth::user())
                <p>
                    <a href="{{ route('labels.edit', $label) }}">{{ __('Edit') }}</a>
                    {{ html()->form('DELETE', route('labels.destroy', $label))->open() }}
                        {{ html()->submit('Delete') }}
                    {{ html()->form()->close() }}
                </p>
            @endif
        </div>
    </div>
</x-app-layout>
