<?php

namespace App\Filament\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\ViewField;
use App\Helpers\Workflow;
use IcehouseVentures\LaravelMermaid\Facades\Mermaid;

class ViewWorkflow extends Action
{

    private string $uniqid;

    private ?Workflow $workflow = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uniqid = 'workflow-editor-' . uniqid();

        $this->outlined();

        $this->schema([
            ViewField::make('builder')
                ->hiddenLabel()
                ->view('filament.forms.components.workflow-builder', [
                    'uniqid' => $this->uniqid
                ]),
        ]);

        $this->modalSubmitAction(false);

        $this->modalCancelAction(false);

        $this->stickyModalHeader();

        $this->label('Workflow');

        $this->modalHeading('Workflow Editor');

        $this->workflow = new Workflow;
        $diagram = $this->workflow->mermaidFormat();
        // $mermaidData = Mermaid::build()->generateDiagramFromArray($diagram);
        $this->fillForm(fn() => ['builder' => $diagram]);
    }

    public static function getDefaultName(): ?string
    {
        return 'workflow-editor';
    }

    public function workflow(bool | Closure $workflow): static
    {
        $this->workflow = new Workflow;

        return $this;
    }

}
