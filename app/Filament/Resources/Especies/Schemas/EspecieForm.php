<?php

namespace App\Filament\Resources\Especies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EspecieForm
{
    public static function configure(Schema $schema): Schema
    {
        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
        ];

        return $schema
            ->components([
                Section::make('Especie')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nombre_cientifico')
                            ->label('Nombre científico')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        TextInput::make('nombre_comun')
                            ->label('Nombre común')
                            ->maxLength(100),
                        TextInput::make('grupo')
                            ->label('Grupo')
                            ->required()
                            ->maxLength(30),
                        Select::make('valoracion')
                            ->label('Valoración')
                            ->required()
                            ->options([
                                'excel·lent' => 'excel·lent',
                                'molt bo' => 'molt bo',
                                'bo' => 'bo',
                                'mediocre' => 'mediocre',
                                'precaució' => 'precaució',
                                'protegida' => 'protegida',
                            ]),
                        TextInput::make('habitat')
                            ->label('Hábitat')
                            ->maxLength(150),
                        TextInput::make('arbolado')
                            ->label('Arbolado')
                            ->maxLength(100),
                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(4)
                            ->columnSpanFull(),
                        TextInput::make('autor_foto')
                            ->label('Autor de la foto')
                            ->maxLength(150),
                        TextInput::make('palabras_clave')
                            ->label('Palabras clave'),
                    ]),
                Section::make('Avisos')
                    ->columns(2)
                    ->schema([
                        Toggle::make('en_avisos')
                            ->label('Incluir en los avisos')
                            ->columnSpanFull(),
                        Select::make('temporada_inicio')
                            ->label('Inicio de temporada')
                            ->options($meses),
                        Select::make('temporada_fin')
                            ->label('Fin de temporada')
                            ->options($meses),
                        TextInput::make('umbral_api10')
                            ->label('Umbral API10')
                            ->numeric()
                            ->step(0.1),
                        TextInput::make('provincia')
                            ->label('Provincia')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('tipo_clima')
                            ->label('Tipo de clima')
                            ->required()
                            ->maxLength(30),
                    ]),
            ]);
    }
}