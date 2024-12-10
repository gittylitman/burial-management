<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Rules\Identity;
use App\Services\ConnectGov;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Support\Facades\Cache;
use Filament\Forms\Get;

class CreateClient extends CreateRecord
{
    use HasWizard;

    protected static string $resource = ClientResource::class;

    protected function afterCreate(): void
    {
        session(['has_representative' => false]);
    }

    protected function beforeFill(): void
    {
        session(['has_representative' => false]);
    }

    protected function getSteps(): array
    {
        return [
            Step::make('client_details')
                ->label(__('client details'))
                ->icon('heroicon-o-user')
                ->schema([
                    Section::make()
                        ->schema([
                            Toggle::make('has_representative')
                                ->label(__('Submitted by a representative'))
                                ->columnSpanFull()
                                ->live()
                                ->reactive()
                                ->afterStateUpdated(fn ($state) => session(['has_representative' => $state])),
                            TextInput::make('identity')
                                ->label(__('identity'))
                                ->unique(ignoreRecord: true)
                                ->rules([new Identity])
                                ->required()
                                ->maxLength(9)
                                ->minLength(7),
                            TextInput::make('name')
                                ->label(__('name'))
                                ->required(),
                            TextInput::make('city')
                                ->label(__('city'))
                                ->required(),
                            TextInput::make('nation')
                                ->label(__('nation'))
                                ->required(),
                            TextInput::make('religion')
                                ->label(__('religion'))
                                ->required(),
                            TextInput::make('phone')
                                ->label(__('phone'))
                                ->tel()
                                ->maxLength(10),
                            DatePicker::make('death_date')
                                ->label(__('death date'))
                                ->maxDate(now()),
                            Select::make('burial_city')
                                ->label(__('burial city'))
                                ->live()
                                ->reactive()
                                ->options(
                                    fn () => Cache::remember('burial_city', 45 * 60, function () {
                                        return app(ConnectGov::class)->getCemeteryCities();
                                    })
                                )
                                ->required()
                                ->afterStateUpdated(function (Get $get) {
                                    session(['burial_city' => $get('burial_city')]);
                                }),
                        ])
                        ->columns(3),
                    ]),
            Step::make('grave_details')
                ->label(__('grave details'))
                ->icon('heroicon-o-building-library')
                ->schema([
                    Section::make()
                        ->relationship('grave')
                        ->schema([
                            Select::make('cemetery')
                                ->label(__('cemetery'))
                                ->live()
                                ->reactive()
                                ->options(
                                    fn () => (app(ConnectGov::class)->getCemeteryByCity(session('burial_city'))) ?? []
                                )
                                ->required(),
                            TextInput::make('burial_type')
                                ->label(__('burial type'))
                                ->required(),
                            TextInput::make('plot')
                                ->label(__('plot'))
                                ->required(),
                            TextInput::make('block')
                                ->label(__('block'))
                                ->required(),
                            TextInput::make('chevra_kadisha')
                                ->label(__('chevra kadisha'))
                                ->required(),
                            TextInput::make('price')
                                ->label(__('price'))
                                ->numeric()
                                ->required(),
                        ])
                        ->columns(3),
                ]),
            Step::make('representative_details')
                ->visible(session('has_representative'))
                ->label(__('representative details'))
                ->icon('heroicon-o-user')
                ->schema([
                    Section::make()
                        ->relationship('representative')
                        ->schema([
                            TextInput::make('name')
                                ->label(__('name'))
                                ->required(),
                            TextInput::make('identity')
                                ->label(__('identity'))
                                ->unique(ignoreRecord: true)
                                ->rules([new Identity])
                                ->maxLength(9)
                                ->minLength(7)
                                ->required(),
                            TextInput::make('city')
                                ->label(__('city'))
                                ->required(),
                            TextInput::make('phone')
                                ->label(__('phone'))
                                ->tel()
                                ->required(),
                            TextInput::make('email')
                                ->label(__('email'))
                                ->email()
                                ->required(),
                            TextInput::make('relation')
                                ->label(__('relation'))
                                ->required(),
                        ])
                        ->columns(3),
                ]),
        ];        
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
