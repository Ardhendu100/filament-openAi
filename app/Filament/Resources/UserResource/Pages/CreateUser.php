<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Pages\Actions\Action;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\CreateUser as PagesCreateUser;

class CreateUser extends PagesCreateUser
{
    protected static string $resource = UserResource::class;

    protected function getCreateAnotherFormAction(): Action
    {
        return Action::make('createAnother')
            ->label(__('redqueen-ivf-system::redqueen-ivf-system.button.save_&_create_another'))
            ->action('createAnother')
            ->keyBindings(['mod+shift+s'])
            ->color('secondary');
    }
}
