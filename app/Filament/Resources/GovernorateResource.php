<?php

namespace App\Filament\Resources;

use Closure;
use App\Models\Governorate;
use Illuminate\Support\Str;
use Filament\{Tables, Forms};
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use App\Filament\Filters\DateRangeFilter;
use Filament\Resources\{Form, Table, Resource};
use App\Filament\Resources\GovernorateResource\Pages;

class GovernorateResource extends Resource
{
    protected static ?string $model = Governorate::class;

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
                        ->required()
                        ->disabled()
                        ->placeholder('Key')
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
                Tables\Columns\TextColumn::make('key')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
            ])
            ->filters([DateRangeFilter::make('created_at')]);
    }

    public static function getRelations(): array
    {
        return [
            GovernorateResource\RelationManagers\CitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGovernorates::route('/'),
            'create' => Pages\CreateGovernorate::route('/create'),
            'view' => Pages\ViewGovernorate::route('/{record}'),
            'edit' => Pages\EditGovernorate::route('/{record}/edit'),
        ];
    }
}
