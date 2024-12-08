<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
                                        // ->rules([new Identity])
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
                                    DatePicker::make('death_date')
                                        ->label(__('death date')),
                                    TextInput::make('burial_city')
                                        ->label(__('burial city'))
                                        ->required(),
                                    TextInput::make('burial_type')
                                        ->label(__('burial type'))
                                        ->required(),
                                    TextInput::make('cemetery')
                                        ->label(__('cemetery'))
                                        ->required(),
                                    TextInput::make('phone')
                                        ->label(__('phone'))
                                        ->tel()
                                        ->maxLength(10)
                                        ->required(),
                                ])
                                ->columns(2)
                        ]),
                    Wizard\Step::make('grave_details')
                        ->label(__('grave details'))
                        ->schema([   
                            Section::make()
                                ->relationship('grave')
                                ->schema([
                                    TextInput::make('cemetery')
                                    ->label(__('cemetery'))
                                    ->required(),
                                ])
                                ->columns(2)
                        ]),
                    Wizard\Step::make('Billing')
                        ->schema([
                            // ...
                        ]),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
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
