<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser as PagesCreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser as PagesEditUser;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\Facades\Hash;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\CreateUser;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\EditUser;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\ListUsers;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\ViewUser;
use Phpsa\FilamentAuthentication\Resources\UserResource as FilamentUserResource;
use Phpsa\FilamentPasswordReveal\Password;

class UserResource extends FilamentUserResource
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $recordTitleAttribute = 'name';
    public function __construct()
    {
        static::$model = config('filament-authentication.models.User');
    }
    protected static function getNavigationGroup(): ?string
    {
        return strval(__('filament-authentication::filament-authentication.section.group'));
    }
    public static function getLabel(): string
    {
        return strval(__('filament-authentication::filament-authentication.section.user'));
    }
    public static function getPluralLabel(): string
    {
        return strval(__('filament-authentication::filament-authentication.section.users'));
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->disableAutocomplete()
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.name')))
                            ->required(),
                        TextInput::make('email')
                            ->required()
                            ->disableAutocomplete()
                            ->email()
                            ->unique(table: static::$model, ignorable: fn ($record) => $record)
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.email'))),
                        TextInput::make('password')
                            ->same('passwordConfirmation')
                            ->password()
                            ->disableAutocomplete()
                            ->maxLength(255)
                            ->required(fn ($component, $get, $livewire, $model, $record, $set, $state) => $record === null)
                            ->dehydrateStateUsing(fn ($state) => !empty($state) ? Hash::make($state) : '')
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.password'))),
                        TextInput::make('passwordConfirmation')
                            ->password()
                            ->dehydrated(false)
                            ->disableAutocomplete()
                            ->maxLength(255)
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.confirm_password'))),
                        TextInput::make('username')
                            ->required()
                            ->disableAutocomplete()
                            ->unique(table: static::$model, ignorable: fn ($record) => $record)
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.username'))),
                        TextInput::make('api_key')
                            ->required()
                            ->disableAutocomplete()
                            ->unique(table: static::$model, ignorable: fn ($record) => $record)
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.api_key'))),
                        Password::make('api_secret')
                            ->autocomplete('new_password')
                            ->revealable()
                            ->copyable()
                            ->required()
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.api_secret'))),
                        Select::make('roles')
                            ->multiple()
                            ->required()
                            ->relationship('roles', 'name')
                            ->preload(config('filament-authentication.preload_roles'))
                            ->label(strval(__('filament-authentication::filament-authentication.field.user.roles'))),
                    ])->columns(2),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label(strval(__('filament-authentication::filament-authentication.field.id'))),
                TextColumn::make('username')
                    ->label(strval(__('filament-authentication::filament-authentication.field.user.username'))),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label(strval(__('filament-authentication::filament-authentication.field.user.email'))),
                TagsColumn::make('roles.name')
                    ->label(strval(__('filament-authentication::filament-authentication.field.user.roles'))),
                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->label(strval(__('filament-authentication::filament-authentication.field.user.created_at'))),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label(strval(__('filament-authentication::filament-authentication.filter.verified')))
                    ->nullable(),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => PagesCreateUser::route('/create'),
            'edit' => PagesEditUser::route('/{record}/edit'),
            'view' => ViewUser::route('/{record}'),
        ];
    }
}
