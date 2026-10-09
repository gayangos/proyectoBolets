<?php

namespace App\Filament\Resources\Especies;

use App\Filament\Resources\Especies\Pages\CreateEspecie;
use App\Filament\Resources\Especies\Pages\EditEspecie;
use App\Filament\Resources\Especies\Pages\ListEspecies;
use App\Filament\Resources\Especies\Schemas\EspecieForm;
use App\Filament\Resources\Especies\Tables\EspeciesTable;
use App\Models\Especie;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EspecieResource extends Resource
{
    protected static ?string $model = Especie::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nombre_cientifico';

    public static function form(Schema $schema): Schema
    {
        return EspecieForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EspeciesTable::configure($table);
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
            'index' => ListEspecies::route('/'),
            'create' => CreateEspecie::route('/create'),
            'edit' => EditEspecie::route('/{record}/edit'),
        ];
    }
}
