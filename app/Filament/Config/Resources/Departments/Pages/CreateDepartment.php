<?php

namespace App\Filament\Config\Resources\Departments\Pages;

use App\Filament\Config\Resources\Departments\DepartmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;
}
