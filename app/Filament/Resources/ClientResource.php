<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use App\Rules\Identity;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;

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
                                    TextInput::make('burial_city')
                                        ->label(__('burial city'))
                                        ->required(),
                                    TextInput::make('burial_type')
                                        ->label(__('burial type'))
                                        ->required(),
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
                                    TextInput::make('cemetery')
                                        ->label(__('cemetery'))
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
                                    Toggle::make('has_representative')
                                        ->label(__('Submitted by a representative'))
                                        ->columnSpanFull(),
                                    TextInput::make('name')
                                        ->label(__('name'))
                                        ->requiredIfAccepted('has_representative'),
                                    TextInput::make('identity')
                                        ->label(__('identity'))
                                        ->unique(ignoreRecord: true)
                                        ->rules([new Identity])
                                        ->maxLength(9)
                                        ->minLength(7)
                                        ->requiredIfAccepted('has_representative'),
                                    TextInput::make('city')
                                        ->label(__('city'))
                                        ->requiredIfAccepted('has_representative'),
                                    TextInput::make('phone')
                                        ->label(__('phone'))
                                        ->tel()
                                        ->requiredIfAccepted('has_representative'),
                                    TextInput::make('email')
                                        ->label(__('email'))
                                        ->email()
                                        ->requiredIfAccepted('has_representative'),
                                    TextInput::make('relation')
                                        ->label(__('relation'))
                                        ->requiredIfAccepted('has_representative'),
                                ])
                                ->columns(3),
                        ]),                        
                ])
                ->columnSpanFull()
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
            ])
            ->filters([
                SelectFilter::make('grave.cemetery')
                    ->label(__('cemetery'))
                    ->multiple()
                    //TODO get it from API
                    ->options([]),
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
