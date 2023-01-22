<?php

namespace App\Filament\Resources;

use App\Models\Seat;
use Filament\{Tables, Forms};
use Filament\Resources\{Form, Table, Resource};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Filament\Filters\DateRangeFilter;
use App\Filament\Resources\SeatResource\Pages;

class SeatResource extends Resource
{
    protected static ?string $model = Seat::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'referance';

    protected static ?string $navigationGroup = 'Main';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                Grid::make(['default' => 0])->schema([
                    TextInput::make('referance')
                        ->rules(['max:255', 'string'])
                        ->required()
                        ->placeholder('Referance')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TextInput::make('number')
                        ->rules(['numeric'])
                        ->required()
                        ->numeric()
                        ->placeholder('Number')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    Select::make('line')
                        ->rules(['in:A,B'])
                        ->required()
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
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                Tables\Columns\TextColumn::make('referance')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
                Tables\Columns\TextColumn::make('number')
                    ->toggleable()
                    ->searchable(true, null, true),
                Tables\Columns\TextColumn::make('line')
                    ->toggleable()
                    ->searchable()
                    ->enum([
                        'A' => 'A',
                        'B' => 'B',
                    ]),
            ])
            ->filters([DateRangeFilter::make('created_at')]);
    }

    public static function getRelations(): array
    {
        return [
            SeatResource\RelationManagers\PassengersRelationManager::class,
            SeatResource\RelationManagers\BusesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeats::route('/'),
            'create' => Pages\CreateSeat::route('/create'),
            'view' => Pages\ViewSeat::route('/{record}'),
            'edit' => Pages\EditSeat::route('/{record}/edit'),
        ];
    }
}
