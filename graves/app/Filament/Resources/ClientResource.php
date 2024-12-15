<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use App\Rules\Identity;
// use App\Services\ConnectionGov;
use App\Tables\Columns\cemeteryUrl;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function getModelLabel(): string
    {
        return __('Client');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Clients');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('client_details')
                        ->label(__('client details'))
                        ->icon('heroicon-o-user')
                        ->schema([
                            Section::make()
                                ->schema([
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
                                        ->options(function () {
                                            $url = config('services.gov.url');
                                            $response = Http::get($url)->json();
                                            return collect($response['result']['records'])->filter(function ($item) {
                                                return isset($item['SETL_NAME']) && $item['SETL_NAME'] !== null;
                                            })
                                            ->map(function ($item) {
                                                return [$item['SETL_NAME'] => $item['SETL_NAME']];
                                            })
                                            ->unique()
                                            ->values()
                                            ->collapse()
                                            ->all();
                                        })
                                        ->required()
                                        ->afterStateUpdated(function (Get $get) {
                                            session(['burial_city' => $get('burial_city')]);
                                        }),
                                ])
                                ->columns(3),
                        ]),
                    Wizard\Step::make('grave_details')
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
                                        // ->options(
                                        //     fn () => (app(ConnectionGov::class)->getCemeteryByCity(session('burial_city'))) ?? []
                                        // )
                                        ->options(function () {
                                            $url = config('services.gov.url');
                                            $response = Http::get($url)->json();
                                            return collect($response['result']['records'])  ->filter(function ($record){
                                                return $record['SETL_NAME'] === session('burial_city');
                                            })
                                            ->map(function ($item) {
                                                return [$item['NAME'] => $item['NAME']];
                                            })
                                            ->values()
                                            ->collapse()
                                            ->all();
                                        })
                                        ->afterStateUpdated(function (Get $get) {
                                            session(['cemetery' => $get('cemetery')]);
                                        })
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
                    Wizard\Step::make('representative_details')
                        ->label(__('representative details (optional)'))
                        ->icon('heroicon-o-user')
                        ->schema([
                            Section::make()
                                ->relationship('representative')
                                ->schema([
                                    TextInput::make('name')
                                        ->label(__('name')),
                                    TextInput::make('identity')
                                        ->label(__('identity'))
                                        ->unique(ignoreRecord: true)
                                        ->rules([new Identity])
                                        ->maxLength(9)
                                        ->minLength(7),
                                    TextInput::make('city')
                                        ->label(__('city')),
                                    TextInput::make('phone')
                                        ->label(__('phone'))
                                        ->tel(),
                                    TextInput::make('email')
                                        ->label(__('email'))
                                        ->email(),
                                    TextInput::make('relation')
                                        ->label(__('relation')),
                                ])
                                ->columns(3),
                        ]),
                ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'identity')
                    ->label(__('identity'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'phone')
                    ->label(__('phone'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'nation')
                    ->label(__('nation'))
                    ->sortable(),
                TextColumn::make(name: 'city')
                    ->label(__('city'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'grave.cemetery')
                    ->label(__('cemetery'))
                    ->searchable()
                    ->sortable(),
                cemeteryUrl::make(name: 'cemetery_url')
                    ->label(__('Cemetery url')),
            ])
            ->filters([
                SelectFilter::make('grave.cemetery')
                    ->label(__('cemetery'))
                    ->multiple()
                    ->options(function () {
                        $url = config('services.gov.url');
                        $response = Http::get($url)->json();
                        return collect($response['result']['records'])->filter(function ($item) {
                            return isset($item['SETL_NAME']) && $item['SETL_NAME'] !== null;
                        })
                        ->map(function ($item) {
                            return [$item['SETL_NAME'] => $item['SETL_NAME']];
                        })
                        ->unique()
                        ->values()
                        ->collapse()
                        ->all();
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
