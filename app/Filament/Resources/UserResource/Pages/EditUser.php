<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\EditUser as PagesEditUser;

class EditUser extends PagesEditUser
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            Actions\ViewAction::make(),
        ];
    }
}
