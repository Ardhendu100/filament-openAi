<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClinicResource\Pages;
use App\Models\Clinic;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
class ClinicResource extends Resource
{
    protected static ?string $model = Clinic::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('redqueen-ivf-system::redqueen-ivf-system.section.clinic'))
                    ->schema([
                        TextInput::make('name')
                            ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.name')))
                            ->placeholder(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.name')))
                            ->disableAutocomplete()
                            ->required(),
                        TextInput::make('email')
                            ->disableAutocomplete()
                            ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.email')))
                            ->placeholder(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.email')))
                            ->required(),
                        Select::make('country')
                            ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.country'))),
                        Select::make('state')
                            ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.state')))
                            ->placeholder(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.state'))),
                        TextInput::make('city')
                            ->disableAutocomplete()
                            ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.city'))),
                        TextInput::make('postal_code')
                            ->disableAutocomplete()
                            ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.postal_code')))
                            ->placeholder(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.postal_code'))),
                        TextInput::make('tax_id_number')
                            ->disableAutocomplete()
                            ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.tax_id_number')))
                            ->placeholder(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.tax_id_number'))),
                        TextInput::make('address')
                            ->disableAutocomplete()
                            ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.address')))
                            ->placeholder(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.address'))),
                        TextInput::make('address_2')
                            ->disableAutocomplete()
                            ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.address_2')))
                            ->placeholder(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.address_2'))),
                    ]),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.column.input.name')))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.email')))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.city')))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('state')
                    ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.state')))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('country')
                    ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.country')))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListClinics::route('/'),
            'create' => Pages\CreateClinic::route('/create'),
            'edit' => Pages\EditClinic::route('/{record}/edit'),
        ];
    }
}
