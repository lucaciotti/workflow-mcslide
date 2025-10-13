<?php

namespace App\Providers\Filament\Core\Traits;

use Filament\Facades\Filament;
use App\Providers\Filament\Core\CorePanel;

trait HasCorePanel
{
    public function register(): void
    {
        Filament::registerPanel(
            $this->panel(CorePanel::make()),
        );
    }
}
