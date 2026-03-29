<?php

namespace App\Filament\Config\Resources\Attributes\Pages;

use App\Filament\Config\Resources\Attributes\AttributeResource;
use App\Models\Attribute;
use App\Models\Department;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAttributes extends ListRecords
{
    protected static string $resource = AttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $allCategory = Department::all();
        $tabs = [];
        $tabs['Tutti'] = Tab::make();
        foreach ($allCategory as $cat) {
            $tabs[$cat->name] = Tab::make($cat->name)
                ->badge(Attribute::query()->where('department_id', $cat->id)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('department_id', $cat->id));
        }
        return $tabs;
    }
}
