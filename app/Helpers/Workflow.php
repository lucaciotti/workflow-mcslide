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

    public function getAllStates() {
        $transitions = WorkflowTransition::with(['fromState', 'toState'])->get();
        $states = $transitions
            ->pluck('fromState')
            ->filter(fn(?WorkflowState $state) => !is_null($state))
            ->merge($transitions->pluck('toState'))
            ->unique();

        return $states;
    }

    // Trasforma flusso per MaermaidJs
    public function mermaidFormat(): string
    {
        $mermaid = "";
        $base_theme = config('mermaid.theme') ?? 'default';
        if (in_array($base_theme, ['base', 'forest', 'dark', 'neutral', 'default'])) {
            $mermaid = "%%{\n
                init: {\"theme\": \"$base_theme\"}
                }%%\n";
        }
        $mermaid .= "flowchart TB\n";
        $transitions = WorkflowTransition::with(['fromState', 'toState'])->get();

        $states = $transitions
            ->pluck('fromState')
            ->filter(fn(?WorkflowState $state) => !is_null($state))
            ->merge($transitions->pluck('toState'))
            ->unique();

        $grouped_states = $states->groupBy('department.name');
        $ids_states_grouped = [];
        // $mermaid .= "subgraph One\n";
        // $mermaid .= "22(Start)\n";
        // $mermaid .= "23(Start)\n";
        // $mermaid .= "24(Start)\n";
        // $mermaid .= "25(Start)\n";
        // $mermaid .= "end\n";
        // $mermaid .= "24 --> 23\n";
        foreach ($grouped_states as $group_state => $groupped_states) {
            if ($group_state){
                $mermaid .= "subgraph " . $group_state . "\n";
                foreach ($groupped_states as $state) {
                    $mermaid .= $state->id . "(" . $state->name . ")\n";
                    array_push($ids_states_grouped, $state->id);
                }
                $mermaid .= "end \n";
            }
        }
        $state_not_grouped = $states->whereNotIn('id', $ids_states_grouped);
        // $state_not_grouped = $states->filter(fn(?WorkflowState $state) => !in_array($state->id, $ids_states_grouped));
        foreach ($state_not_grouped as $state) {
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
