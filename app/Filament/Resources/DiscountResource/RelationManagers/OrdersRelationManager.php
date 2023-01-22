<?php

namespace App\Filament\Resources\DiscountResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\{Form, Table};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Resources\RelationManagers\RelationManager;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

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
                        'lg' => 4,
                    ]),

                TextInput::make('email')
                    ->rules(['email'])
                    ->email()
                    ->placeholder('Email')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 4,
                    ]),

                TextInput::make('phone')
                    ->rules(['max:255', 'string'])
                    ->placeholder('Phone')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 4,
                    ]),

                Select::make('trip_id')
                    ->rules(['exists:trips,id'])
                    ->relationship('trip', 'name')
                    ->searchable()
                    ->placeholder('Trip')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                Select::make('payment_type')
                    ->rules(['in:cash,card'])
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
                    ->placeholder('Tax')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                TextInput::make('subtotal_amount')
                    ->rules(['max:255', 'string'])
                    ->placeholder('Subtotal Amount')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),

                TextInput::make('total_amount')
                    ->rules(['max:255', 'string'])
                    ->placeholder('Total Amount')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 6,
                    ]),
            ]),
        ]);
    }

    protected function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->limit(50),
                Tables\Columns\TextColumn::make('email')->limit(50),
                Tables\Columns\TextColumn::make('phone')->limit(50),
                Tables\Columns\TextColumn::make('trip.name')->limit(50),
                Tables\Columns\TextColumn::make('discount.id')->limit(50),
                Tables\Columns\TextColumn::make('payment_type')->enum([
                    'cash' => 'Cash',
                    'card' => 'Card',
                ]),
                Tables\Columns\TextColumn::make('tax')->limit(50),
                Tables\Columns\TextColumn::make('subtotal_amount')->limit(50),
                Tables\Columns\TextColumn::make('total_amount')->limit(50),
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

                MultiSelectFilter::make('trip_id')->relationship(
                    'trip',
                    'name'
                ),

                MultiSelectFilter::make('discount_id')->relationship(
                    'discount',
                    'id'
                ),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }
}
