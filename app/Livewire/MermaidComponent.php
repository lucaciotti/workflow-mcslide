<?php

namespace App\Livewire;

use Livewire\Component;

class MermaidComponent extends Component
{
    public $mermaid;
    
    public function render()
    {
        return <<<'HTML'
        <div>
            <x-mermaid::livewire-component wire:model="mermaid" />
        </div>
        HTML;
    }
}
