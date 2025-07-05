<?php

use function Livewire\Volt\{state, computed, mount};

state([
    'pairs' => [['key' => '', 'value' => '']]
]);

// Add a mount method to initialize with existing data
mount(function ($initialPairs = null) {
    if (!empty($initialPairs)) {
        $this->pairs = $initialPairs;
    }
});

$addPair = function () {
    $this->pairs[] = ['key' => '', 'value' => ''];
};

$removePair = function ($index) {
    if (count($this->pairs) > 1) {
        unset($this->pairs[$index]);
        $this->pairs = array_values($this->pairs);
    }
};

$getJson = computed(function () {
    $result = [];
    foreach ($this->pairs as $pair) {
        if (!empty($pair['key'])) {
            $result[$pair['key']] = $pair['value'];
        }
    }
    return json_encode($result);
});
?>

<div>
    <div class="mb-4">
        <label class="block mb-2">Additional Info:</label>
        <input type="hidden" name="additional_info" value="{{ $this->getJson }}">
        
        @foreach($pairs as $index => $pair)
            <div class="flex mb-2 gap-2">
                <input type="text" placeholder="Key" 
                       wire:model.live="pairs.{{ $index }}.key" 
                       class="w-1/3">
                <input type="text" placeholder="Value" 
                       wire:model.live="pairs.{{ $index }}.value" 
                       class="w-1/3">
                <button type="button" wire:click="removePair({{ $index }})" 
                        class="px-2 py-1 bg-red-500 text-white rounded">
                    Remove
                </button>
            </div>
        @endforeach
        
        <button type="button" wire:click="addPair" 
                class="px-3 py-1 bg-blue-500 text-white rounded mt-2">
            Add Field
        </button>
    </div>
    
    <div class="mt-2 text-sm text-gray-600">
        Current JSON: <pre>{{ $this->getJson }}</pre>
    </div>
</div>