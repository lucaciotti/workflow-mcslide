<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle(@js($getStatePath())) }"
        {{ $getExtraAttributeBag() }}
    >
        {{-- @dd($getState()) --}}
        {{-- <x-mermaid::component :data="$getState()" /> --}}
        @livewire(App\Livewire\MermaidComponent::class, ['mermaid' => $getState()])
        {{-- Interact with the `state` property in Alpine.js --}}
    </div>
</x-dynamic-component>
