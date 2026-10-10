<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_admin')
                    ->label('Administrador')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Alta')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->label('Correo verificado')
                    ->date('d/m/Y')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_admin')
                    ->label('Administrador'),
            ])
            ->recordActions([
                EditAction::make(),
                // Nadie puede borrarse a sí mismo desde el panel.
                DeleteAction::make()
                    ->hidden(fn ($record) => $record->is(auth()->user())),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}