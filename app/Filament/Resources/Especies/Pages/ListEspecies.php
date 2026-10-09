<?php

namespace App\Filament\Resources\Especies\Pages;

use App\Filament\Resources\Especies\EspecieResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEspecies extends ListRecords
{
    protected static string $resource = EspecieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
