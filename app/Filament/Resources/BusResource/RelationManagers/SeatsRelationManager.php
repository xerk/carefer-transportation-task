<?php

namespace App\Filament\Resources\BusResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\{Form, Table};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class SeatsRelationManager extends RelationManager
{
    protected static string $relationship = 'seats';

    protected static ?string $recordTitleAttribute = 'referance';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 0])->schema([
                TextInput::make('referance')
                    ->rules(['max:255', 'string'])
                    ->placeholder('Referance')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                TextInput::make('number')
                    ->rules(['numeric'])
                    ->numeric()
                    ->placeholder('Number')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                Select::make('line')
                    ->rules(['in:a,b'])
                    ->searchable()
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                    ])
                    ->placeholder('Line')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('referance')->limit(50),
                Tables\Columns\TextColumn::make('number'),
                Tables\Columns\TextColumn::make('line')->enum([
                    'A' => 'A',
                    'B' => 'B',
                ]),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (
                                    Builder $query,
                                    $date
                                ): Builder => $query->whereDate(
                                    'created_at',
                                    '>=',
                                    $date
                                )
                            )
                            ->when(
                                $data['created_until'],
                                fn (
                                    Builder $query,
                                    $date
                                ): Builder => $query->whereDate(
                                    'created_at',
                                    '<=',
                                    $date
                                )
                            );
                    }),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }
}
