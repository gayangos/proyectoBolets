<?php

namespace App\Filament\Resources\Especies\Pages;

use App\Filament\Resources\Especies\EspecieResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEspecie extends EditRecord
{
    protected static string $resource = EspecieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
