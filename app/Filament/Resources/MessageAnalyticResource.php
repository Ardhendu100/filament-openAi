<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageAnalyticResource\Pages;
use App\Models\MessageAnalytic;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class MessageAnalyticResource extends Resource
{
    protected static ?string $model = MessageAnalytic::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('user_id')
                        ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.user')))
                        ->afterStateHydrated(function ($component, $state) {
                            return $component->state(User::find($state)?->email);
                        }),
                    TextInput::make('message_owner_id')
                        ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.message_owner_id'))),
                    Textarea::make('input_message')
                        ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input.message'))),
                    Textarea::make('phi_detect_data')
                        ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.phidetect')))
                        ->afterStateHydrated(fn ($state, $component) => prettifyJson($state, $component)),  // add helper function which prettify Json response
                    Textarea::make('masked_data')
                        ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.mask'))),
                    Textarea::make('output_message')
                        ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.output')))
                        ->afterStateHydrated(fn ($state, $component) => prettifyJson($state, $component)),
                    Textarea::make('response_feedback')
                        ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.feedback')))
                        ->hidden(fn ($state) => ($state == 'null'))
                        ->afterStateHydrated(fn ($state, $component) => prettifyJson($state, $component))
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.email')
                    ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.table.column.email')))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('input_message')
                    ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.table.column.input')))
                    ->searchable()
                    ->limit(50)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessageAnalytics::route('/'),
            'create' => Pages\CreateMessageAnalytic::route('/create'),
            'view' => Pages\ViewMessageAnalytic::route('/{record}'),
            'edit' => Pages\EditMessageAnalytic::route('/{record}/edit'),
        ];
    }
}
