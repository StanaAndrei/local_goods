<?php
use function Livewire\Volt\{state, layout};
state(['message' => 'Volt is working!']);
layout('layouts.app');
?>
<div>{{ $message }}</div>