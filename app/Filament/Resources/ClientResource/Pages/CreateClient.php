<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Models\Grave;
use App\Models\Representative;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $rep_details = collect($this->data['representative'])->filter()->all();
        $representative = null;
        if((count($rep_details) > 0)){
            $representative = Representative::create($this->data['representative']);
        }        
        $data['representative_id'] = $representative ? $representative->id : null;
        $grave = Grave::create($this->data['grave']);
        $data['grave_id'] = $grave->id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
