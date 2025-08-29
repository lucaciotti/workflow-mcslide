<?php

namespace App\Helpers;

use App\Models\WorkflowState;
use App\Models\WorkflowTransition;

class Workflow
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    // Trasforma flusso per MaermaidJs
    public function mermaidFormat(): string
    {
        $mermaid = "flowchart TB\n";
        $transitions = WorkflowTransition::with(['fromState', 'toState'])->get();

        $states = $transitions
            ->pluck('fromState')
            ->filter(fn(?WorkflowState $state) => !is_null($state))
            ->merge($transitions->pluck('toState'))
            ->unique();

        // $mermaid .= "subgraph One\n";
        // $mermaid .= "22(Start)\n";
        // $mermaid .= "23(Start)\n";
        // $mermaid .= "24(Start)\n";
        // $mermaid .= "25(Start)\n";
        // $mermaid .= "end\n";
        // $mermaid .= "24 --> 23\n";
        foreach ($states as $state) {
            $mermaid .= $state->id . "(" . $state->name . ")\n";
        }

        foreach ($transitions as $transition) {
            // $mermaid .= ($transition->fromState?->id ?? 0) . " -- " . $transition->action->name . " --> " . $transition->toState->id . "\n";
            $mermaid .= ($transition->fromState?->id ?? 0) . " --> " . $transition->toState->id . "\n";
        }
        // return preg_split("/\r\n|\n|\r/", $mermaid);
        return $mermaid;
    }


}
