<?php

namespace App\Filament\Resources\WorkTypeResource\Pages;

use App\Filament\Resources\WorkTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageWorkTypes extends ManageRecords
{
    protected static string $resource = WorkTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
