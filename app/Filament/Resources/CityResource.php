<?php

namespace App\Filament\Resources;

use Closure;
use App\Models\City;
use Illuminate\Support\Str;
use Filament\{Tables, Forms};
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\CityResource\Pages;
use Filament\Resources\{Form, Table, Resource};

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Main';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                Grid::make(['default' => 0])->schema([
                    Select::make('governorate_id')
                        ->rules(['exists:governorates,id'])
                        ->required()
                        ->relationship('governorate', 'name')
                        ->searchable()
                        ->placeholder('Governorate')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TextInput::make('name')
                        ->rules(['max:255', 'string'])
                        ->required()
                        ->afterStateUpdated(function (Closure $get, Closure $set, ?string $state) {
                            $set('key', Str::slug($state));
                        })
                        ->reactive()
                        ->placeholder('Name')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TextInput::make('key')
                        ->rules(['max:255', 'string'])
                        ->nullable()
                        ->disabled()
                        ->placeholder('Key')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 8,
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
                Tables\Columns\TextColumn::make('governorate.name')
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
                Tables\Columns\TextColumn::make('key')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
            ])
            ->filters([
                SelectFilter::make('governorate_id')
                    ->relationship('governorate', 'name')
                    ->indicator('Governorate')
                    ->multiple()
                    ->label('Governorate'),
            ]);
    }

    public static function getRelations(): array
    {
        return [CityResource\RelationManagers\StationsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'view' => Pages\ViewCity::route('/{record}'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }
}
