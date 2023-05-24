<?php

namespace App\Filament\Resources\ClinicResource\Pages;

use App\Filament\Resources\ClinicResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Pages\Actions\Action;
class CreateClinic extends CreateRecord
{
    protected static string $resource = ClinicResource::class;

    protected function getCreateAnotherFormAction(): Action
    {
        return Action::make('createAnother')
            ->label(__('redqueen-ivf-system::redqueen-ivf-system.button.save_&_create_another'))
            ->action('createAnother')
            ->keyBindings(['mod+shift+s'])
            ->color('secondary');
    }
}
