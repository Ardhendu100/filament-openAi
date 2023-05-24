<?php

namespace App\Filament\Resources\MessageAnalyticResource\Pages;

use App\Filament\Resources\MessageAnalyticResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMessageAnalytic extends EditRecord
{
    protected static string $resource = MessageAnalyticResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
