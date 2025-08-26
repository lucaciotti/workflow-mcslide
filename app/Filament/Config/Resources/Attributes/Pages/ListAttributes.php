<?php

namespace App\Filament\Config\Resources\Attributes\Pages;

use App\Filament\Config\Resources\Attributes\AttributeResource;
use App\Models\Attribute;
use App\Models\AttributeCategory;
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
        $allCategory = AttributeCategory::all();
        $tabs = [];
        $tabs['Tutti'] = Tab::make();
        foreach ($allCategory as $cat) {
            $tabs[$cat->name] = Tab::make($cat->name)
                ->badge(Attribute::query()->where('attribute_category_id', $cat->id)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('attribute_category_id', $cat->id));
        }
        return $tabs;
    }
}
