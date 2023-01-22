<?php

namespace App\Filament\Resources;

use App\Models\Passenger;
use Filament\{Tables, Forms};
use Filament\Resources\{Form, Table, Resource};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Filters\DateRangeFilter;
use App\Filament\Resources\PassengerResource\Pages;

class PassengerResource extends Resource
{
    protected static ?string $model = Passenger::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'id';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                Grid::make(['default' => 0])->schema([
                    Select::make('type')
                        ->rules(['in:guest,user'])
                        ->required()
                        ->searchable()
                        ->options([
                            'guest' => 'Guest',
                            'user' => 'User',
                        ])
                        ->placeholder('Type')
                        ->default('guest')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    Select::make('user_id')
                        ->rules(['exists:users,id'])
                        ->nullable()
                        ->relationship('user', 'name')
                        ->searchable()
                        ->placeholder('User')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    Select::make('seat_id')
                        ->rules(['exists:seats,id'])
                        ->required()
                        ->relationship('seat', 'referance')
                        ->searchable()
                        ->placeholder('Seat')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    Select::make('order_id')
                        ->rules(['exists:orders,id'])
                        ->required()
                        ->relationship('order', 'name')
                        ->searchable()
                        ->placeholder('Order')
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
                Tables\Columns\TextColumn::make('type')
                    ->toggleable()
                    ->searchable()
                    ->enum([
                        'guest' => 'Guest',
                        'user' => 'User',
                    ]),
                Tables\Columns\TextColumn::make('user.name')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('seat.referance')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('order.name')
                    ->toggleable()
                    ->limit(50),
            ])
            ->filters([
                DateRangeFilter::make('created_at'),

                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->indicator('User')
                    ->multiple()
                    ->label('User'),

                SelectFilter::make('seat_id')
                    ->relationship('seat', 'referance')
                    ->indicator('Seat')
                    ->multiple()
                    ->label('Seat'),

                SelectFilter::make('order_id')
                    ->relationship('order', 'name')
                    ->indicator('Order')
                    ->multiple()
                    ->label('Order'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPassengers::route('/'),
            'create' => Pages\CreatePassenger::route('/create'),
            'view' => Pages\ViewPassenger::route('/{record}'),
            'edit' => Pages\EditPassenger::route('/{record}/edit'),
        ];
    }
}
