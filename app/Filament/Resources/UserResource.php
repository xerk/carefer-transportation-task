<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\{Tables, Forms};
use Filament\Resources\{Form, Table, Resource};
use Livewire\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use App\Filament\Filters\DateRangeFilter;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'User Management';


    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                Grid::make(['default' => 0])->schema([
                    FileUpload::make('profile_photo_path')
                        ->rules(['image', 'max:1024'])
                        ->nullable()
                        ->image()
                        ->placeholder('Profile Photo Path')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 8,
                        ]),

                    TextInput::make('name')
                        ->rules(['max:255', 'string'])
                        ->required()
                        ->placeholder('Name')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TextInput::make('email')
                        ->rules(['email'])
                        ->required()
                        ->unique(
                            'users',
                            'email',
                            fn (?Model $record) => $record
                        )
                        ->email()
                        ->placeholder('Email')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    TextInput::make('password')
                        ->required()
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => \Hash::make($state))
                        ->required(
                            fn (Component $livewire) => $livewire instanceof
                                Pages\CreateUser
                        )
                        ->placeholder('Password')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 6,
                        ]),

                    Select::make('type')
                        ->rules(['in:admin,passenger,driver'])
                        ->required()
                        ->searchable()
                        ->options([
                            'admin' => 'Admin',
                            'passenger' => 'Passenger',
                            'driver' => 'Driver',
                        ])
                        ->placeholder('Type')
                        ->default('passenger')
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
                Tables\Columns\ImageColumn::make('profile_photo_path')
                    ->toggleable()
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
                Tables\Columns\TextColumn::make('email')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
                Tables\Columns\TextColumn::make('type')
                    ->toggleable()
                    ->searchable()
                    ->enum([
                        'admin' => 'Admin',
                        'passenger' => 'Passenger',
                        'driver' => 'Driver',
                    ]),
            ])
            ->filters([DateRangeFilter::make('created_at')]);
    }

    public static function getRelations(): array
    {
        return [
            UserResource\RelationManagers\BusesRelationManager::class,
            UserResource\RelationManagers\PassengersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
