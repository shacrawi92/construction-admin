<?php

namespace App\Filament\Resources\MaterialRequestResource\Pages;

use App\Filament\Resources\MaterialRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMaterialRequests extends ManageRecords
{
    protected static string $resource = MaterialRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
