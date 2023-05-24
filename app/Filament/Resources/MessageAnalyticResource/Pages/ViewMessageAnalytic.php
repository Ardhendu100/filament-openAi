<?php

namespace App\Filament\Resources\MessageAnalyticResource\Pages;

use App\Filament\Resources\MessageAnalyticResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMessageAnalytic extends ViewRecord
{
    protected static string $resource = MessageAnalyticResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
}
