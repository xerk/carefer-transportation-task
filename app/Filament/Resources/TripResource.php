<?php

namespace App\Filament\Resources;

use App\Models\Trip;
use Filament\{Tables, Forms};
use Filament\Resources\{Form, Table, Resource};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Filters\DateRangeFilter;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\TripResource\Pages;
use Filament\Forms\Components\TimePicker;

class TripResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Orders';

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

                    TextInput::make('frequent')
                        ->rules(['max:255', 'string'])
                        ->nullable()
                        ->placeholder('Frequent')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    Select::make('pickup_id')
                        ->rules(['exists:stations,id'])
                        ->required()
                        ->preload()
                        ->relationship('pickup', 'name')
                        ->searchable()
                        ->placeholder('Pickup')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    Select::make('destination_id')
                        ->rules(['exists:stations,id'])
                        ->required()
                        ->relationship('destination', 'name')
                        ->searchable()
                        ->preload()
                        ->placeholder('Destination')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    Select::make('bus_id')
                        ->rules(['exists:buses,id'])
                        ->required()
                        ->preload()
                        ->relationship('bus', 'name')
                        ->searchable()
                        ->placeholder('Bus')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    Select::make('type')
                        ->rules(['in:short,long'])
                        ->required()
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
                        ->required()
                        ->numeric()
                        ->placeholder('Distance')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TextInput::make('price')
                        ->rules(['numeric'])
                        ->required()
                        ->numeric()
                        ->placeholder('Price')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TimePicker::make('start_at')
                        ->nullable()
                        ->placeholder('Start At')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TimePicker::make('end_at')
                        ->nullable()
                        ->placeholder('End At')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TextInput::make('cron_experations')
                        ->rules(['max:255', 'string'])
                        ->nullable()
                        ->placeholder('Cron Experations')
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
                            'lg' => 6,
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
                Tables\Columns\TextColumn::make('frequent')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
                Tables\Columns\TextColumn::make('pickup.name')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('destination.name')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('bus.name')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('type')
                    ->toggleable()
                    ->enum([
                        'short' => 'Short',
                        'long' => 'Long',
                    ]),
                Tables\Columns\TextColumn::make('price')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->toggleable()
                    ->dateTime(),
                Tables\Columns\IconColumn::make('active')
                    ->toggleable()
                    ->boolean(),
            ])
            ->filters([
                DateRangeFilter::make('created_at'),

                SelectFilter::make('pickup_id')
                    ->relationship('pickup', 'name')
                    ->indicator('Station')
                    ->multiple()
                    ->label('Station'),

                SelectFilter::make('destination_id')
                    ->relationship('destination', 'name')
                    ->indicator('Station')
                    ->multiple()
                    ->label('Station'),

                SelectFilter::make('bus_id')
                    ->relationship('bus', 'id')
                    ->indicator('Bus')
                    ->multiple()
                    ->label('Bus'),
            ]);
    }

    public static function getRelations(): array
    {
        return [TripResource\RelationManagers\OrdersRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrips::route('/'),
            'create' => Pages\CreateTrip::route('/create'),
            'view' => Pages\ViewTrip::route('/{record}'),
            'edit' => Pages\EditTrip::route('/{record}/edit'),
        ];
    }
}
