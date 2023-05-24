<?php

namespace App\Filament\Resources\MessageAnalyticResource\Pages;

use App\Filament\Resources\MessageAnalyticResource;
use App\Models\MessageAnalytic;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\TextInput;

class ListMessageAnalytics extends ListRecords
{
    protected static string $resource = MessageAnalyticResource::class;

    protected function getActions(): array
    {
        return [

            Actions\Action::make('start-a-new-chat')
                ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.newmessage')))
                ->form([
                    Textarea::make('input_message')
                        ->required()
                        ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.input'))),
                    TextInput::make('message_owner_id')
                        ->label(strval(__('redqueen-ivf-system::redqueen-ivf-system.form.message_owner_id'))),
                ])
                ->action(function (array $data): void {
                    $messageAnalytic = new MessageAnalytic();
                    $messageAnalytic->input_message = $data['input_message'];
                    $messageAnalytic->message_owner_id = $data['message_owner_id'];
                    $messageAnalytic->user_id = auth()->user()->id;
                    $messageAnalytic->save();
                    to_route('filament.resources.message-analytics.view', ['record' => $messageAnalytic]);
                }),
        ];
    }
}
