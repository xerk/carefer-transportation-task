<?php

namespace App\Filament\Resources;

use App\Models\Order;
use Filament\{Tables, Forms};
use Filament\Resources\{Form, Table, Resource};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Filters\DateRangeFilter;
use App\Filament\Resources\OrderResource\Pages;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Orders';


    // disable create order
    public static function canCreate(): bool
    {
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                Grid::make(['default' => 0])->schema([
                    TextInput::make('name')
                        ->rules(['max:255', 'string'])
                        ->nullable()
                        ->placeholder('Name')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 4,
                        ]),

                    TextInput::make('email')
                        ->rules(['email'])
                        ->nullable()
                        ->email()
                        ->placeholder('Email')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 4,
                        ]),

                    TextInput::make('phone')
                        ->rules(['max:255', 'string'])
                        ->nullable()
                        ->placeholder('Phone')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 4,
                        ]),

                    Select::make('trip_id')
                        ->rules(['exists:trips,id'])
                        ->required()
                        ->relationship('trip', 'name')
                        ->searchable()
                        ->placeholder('Trip')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    Select::make('discount_id')
                        ->rules(['exists:discounts,id'])
                        ->nullable()
                        ->relationship('discount', 'id')
                        ->searchable()
                        ->placeholder('Discount')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    Select::make('payment_type')
                        ->rules(['in:cash,card'])
                        ->required()
                        ->searchable()
                        ->options([
                            'cash' => 'Cash',
                            'card' => 'Card',
                        ])
                        ->placeholder('Payment Type')
                        ->default('cash')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TextInput::make('tax')
                        ->rules(['max:255', 'string'])
                        ->nullable()
                        ->placeholder('Tax')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TextInput::make('subtotal_amount')
                        ->rules(['max:255', 'string'])
                        ->nullable()
                        ->placeholder('Subtotal Amount')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TextInput::make('total_amount')
                        ->rules(['max:255', 'string'])
                        ->nullable()
                        ->placeholder('Total Amount')
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
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
                Tables\Columns\TextColumn::make('email')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
                Tables\Columns\TextColumn::make('phone')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
                Tables\Columns\TextColumn::make('trip.name')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('discount.id')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('payment_type')
                    ->toggleable()
                    ->searchable()
                    ->enum([
                        'cash' => 'Cash',
                        'card' => 'Card',
                    ]),
                Tables\Columns\TextColumn::make('tax')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
                Tables\Columns\TextColumn::make('subtotal_amount')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
                Tables\Columns\TextColumn::make('total_amount')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
            ])
            ->filters([
                DateRangeFilter::make('created_at'),

                SelectFilter::make('trip_id')
                    ->relationship('trip', 'name')
                    ->indicator('Trip')
                    ->multiple()
                    ->label('Trip'),

                SelectFilter::make('discount_id')
                    ->relationship('discount', 'id')
                    ->indicator('Discount')
                    ->multiple()
                    ->label('Discount'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrderResource\RelationManagers\PassengersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
