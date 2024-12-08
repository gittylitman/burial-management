<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Models\Grave;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        dd( $this->form->getState());
        // $grave = Grave::create([
        //     'cemetery' => $data->cemetery,
        //     'plot' => $data->plot,
        //     'block' => $data->block,
        //     'city' => $data->city,
        //     'chevra_kadisha' => $data->chevra_kadisha,
        //     'price' => $data->price,
        // ]);

        // $data['grave_id'] = $grave->id;
    
        // return $data;
    }
}
