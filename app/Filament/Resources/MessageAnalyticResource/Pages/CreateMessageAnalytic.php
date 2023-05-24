<?php

namespace App\Filament\Resources\MessageAnalyticResource\Pages;

use App\Filament\Resources\MessageAnalyticResource;
use Filament\Notifications\Collection;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;
use Filament\Pages\Actions\Button;
use Filament\Pages\Actions\Action;
use Livewire\Component as Livewire;


class CreateMessageAnalytic extends CreateRecord
{
    protected static string $resource = MessageAnalyticResource::class;

    protected function getCreateAnotherFormAction(): Action
    {
        return Action::make('createAnother')
            ->label(__('redqueen-ivf-system::redqueen-ivf-system.button.save_&_create_another'))
            ->action('createAnother')
            ->keyBindings(['mod+shift+s'])
            ->color('secondary');
    }
}

