<?php

namespace App\Filament\Resources;

use App\Models\Station;
use Filament\{Tables, Forms};
use Filament\Resources\{Form, Table, Resource};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Filters\DateRangeFilter;
use App\Filament\Resources\StationResource\Pages;

class StationResource extends Resource
{
    protected static ?string $model = Station::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Main';


    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                Grid::make(['default' => 0])->schema([
                    TextInput::make('name')
                        ->rules(['max:255', 'string'])
                        ->required()
                        ->placeholder('Name')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    Select::make('city_id')
                        ->rules(['exists:cities,id'])
                        ->required()
                        ->relationship('city', 'name')
                        ->searchable()
                        ->placeholder('City')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TextInput::make('longitude')
                        ->rules(['numeric'])
                        ->nullable()
                        ->numeric()
                        ->placeholder('Longitude')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TextInput::make('latitude')
                        ->rules(['numeric'])
                        ->nullable()
                        ->numeric()
                        ->placeholder('Latitude')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    Toggle::make('active')
                        ->rules(['boolean'])
                        ->required()
                        ->default('1')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
                Tables\Columns\TextColumn::make('city.name')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('longitude')
                    ->toggleable()
                    ->searchable(true, null, true),
                Tables\Columns\TextColumn::make('latitude')
                    ->toggleable()
                    ->searchable(true, null, true),
                Tables\Columns\IconColumn::make('active')
                    ->toggleable()
                    ->boolean(),
            ])
            ->filters([
                DateRangeFilter::make('created_at'),

                SelectFilter::make('city_id')
                    ->relationship('city', 'name')
                    ->indicator('City')
                    ->multiple()
                    ->label('City'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StationResource\RelationManagers\PickupsRelationManager::class,
            StationResource\RelationManagers\DestinationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStations::route('/'),
            'create' => Pages\CreateStation::route('/create'),
            'view' => Pages\ViewStation::route('/{record}'),
            'edit' => Pages\EditStation::route('/{record}/edit'),
        ];
    }
}
