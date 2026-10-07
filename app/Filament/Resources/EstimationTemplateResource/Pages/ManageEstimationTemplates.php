<?php

namespace App\Filament\Resources\EstimationTemplateResource\Pages;

use App\Filament\Resources\EstimationTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEstimationTemplates extends ManageRecords
{
    protected static string $resource = EstimationTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
