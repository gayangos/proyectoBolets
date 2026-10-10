<?php

namespace App\Filament\Resources\Especies\Tables;

use App\Models\Especie;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class EspeciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('nombre_cientifico')
            ->columns([
                ImageColumn::make('miniatura')
                    ->label('Foto')
                    ->state(fn ($record) => asset('imagenes/miniaturas/' . ($record->foto ?? 'b_' . $record->id . '.jpg')))
                    ->square(),
                TextColumn::make('nombre_cientifico')
                    ->label('Nombre científico')
                    ->extraAttributes(['style' => 'font-style: italic'])
                    ->searchable()
                    ->sortable(),
                TextColumn::make('traduccionBase.nombre_comun')
                    ->label('Nombre común')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('grupo')
                    ->label('Grupo')
                    ->sortable(),
                TextColumn::make('valoracion')
                    ->label('Valoración')
                    ->badge()
                    ->sortable(),
                IconColumn::make('en_avisos')
                    ->label('Avisos')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('traduccionBase.habitat')
                    ->label('Hábitat')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('autor_foto')
                    ->label('Autor de la foto')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('temporada_inicio')
                    ->label('Inicio temporada')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('temporada_fin')
                    ->label('Fin temporada')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('umbral_api10')
                    ->label('Umbral API10')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('en_avisos')
                    ->label('En avisos'),
                SelectFilter::make('valoracion')
                    ->label('Valoración')
                    ->options(fn () => Especie::query()->distinct()->orderBy('valoracion')->pluck('valoracion', 'valoracion')->all()),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
