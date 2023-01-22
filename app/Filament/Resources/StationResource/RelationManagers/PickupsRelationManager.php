<?php

namespace App\Filament\Resources\StationResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\{Form, Table};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Resources\RelationManagers\RelationManager;

class PickupsRelationManager extends RelationManager
{
    protected static string $relationship = 'pickups';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 0])->schema([
                TextInput::make('name')
                    ->rules(['max:255', 'string'])
                    ->placeholder('Name')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                TextInput::make('frequent')
                    ->rules(['max:255', 'string'])
                    ->placeholder('Frequent')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                Select::make('destination_id')
                    ->rules(['exists:stations,id'])
                    ->relationship('destination', 'name')
                    ->searchable()
                    ->placeholder('Destination')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                Select::make('bus_id')
                    ->rules(['exists:buses,id'])
                    ->relationship('bus', 'id')
                    ->searchable()
                    ->placeholder('Bus')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                Select::make('type')
                    ->rules(['in:short,long'])
                    ->searchable()
                    ->options([
                        'short' => 'Short',
                        'long' => 'Long',
                    ])
                    ->placeholder('Type')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                TextInput::make('distance')
                    ->rules(['numeric'])
                    ->numeric()
                    ->placeholder('Distance')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                TextInput::make('price')
                    ->rules(['numeric'])
                    ->numeric()
                    ->placeholder('Price')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                DateTimePicker::make('start_at')
                    ->rules(['date_format:H:i:s'])
                    ->placeholder('Start At')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                DateTimePicker::make('end_at')
                    ->rules(['date_format:H:i:s'])
                    ->placeholder('End At')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                TextInput::make('cron_experations')
                    ->rules(['max:255', 'string'])
                    ->placeholder('Cron Experations')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                Toggle::make('active')
                    ->rules(['boolean'])
                    ->default('1')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->limit(50),
                Tables\Columns\TextColumn::make('frequent')->limit(50),
                Tables\Columns\TextColumn::make('pickup.name')->limit(50),
                Tables\Columns\TextColumn::make('destination.name')->limit(50),
                Tables\Columns\TextColumn::make('bus.id')->limit(50),
                Tables\Columns\TextColumn::make('type')->enum([
                    'short' => 'Short',
                    'long' => 'Long',
                ]),
                Tables\Columns\TextColumn::make('distance'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('start_at')->dateTime(),
                Tables\Columns\TextColumn::make('end_at')->dateTime(),
                Tables\Columns\TextColumn::make('cron_experations')->limit(50),
                Tables\Columns\IconColumn::make('active'),
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

                MultiSelectFilter::make('pickup_id')->relationship(
                    'pickup',
                    'name'
                ),

                MultiSelectFilter::make('destination_id')->relationship(
                    'destination',
                    'name'
                ),

                MultiSelectFilter::make('bus_id')->relationship('bus', 'id'),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }
}
