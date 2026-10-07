<?php

namespace App\Filament\Resources\EstimationResource\Pages;

use App\Filament\Resources\EstimationResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEstimations extends ManageRecords
{
    protected static string $resource = EstimationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
