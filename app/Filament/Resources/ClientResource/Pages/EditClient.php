<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function afterFill(): void
    {
        if($this->record->representative) 
        {
            $this->record->representative->has_representative = true;
            session(['has_representative' => true]);
        }
        else {
            session(['has_representative' => false]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
