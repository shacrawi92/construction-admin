<?php

namespace App\Filament\Resources\LaborLogResource\Pages;

use App\Filament\Resources\LaborLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLaborLogs extends ManageRecords
{
    protected static string $resource = LaborLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
