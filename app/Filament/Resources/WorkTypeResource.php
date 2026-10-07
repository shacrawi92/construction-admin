<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkTypeResource\Pages;
use App\Filament\Resources\WorkTypeResource\RelationManagers;
use App\Models\WorkType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkTypeResource extends Resource
{
    protected static ?string $model = WorkType::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Field Operations';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_en')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name_am')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('name_so')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('category')
                    ->required(),
                Forms\Components\TextInput::make('unit')
                    ->required()
                    ->maxLength(30),
                Forms\Components\TextInput::make('default_unit_rate_etb')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_am')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_so')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('default_unit_rate_etb')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWorkTypes::route('/'),
        ];
    }
}
