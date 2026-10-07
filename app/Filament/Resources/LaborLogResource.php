<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaborLogResource\Pages;
use App\Filament\Resources\LaborLogResource\RelationManagers;
use App\Models\LaborLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaborLogResource extends Resource
{
    protected static ?string $model = LaborLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Field Operations';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('project_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('foreman_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('work_type_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('worker_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('log_date')
                    ->required(),
                Forms\Components\TextInput::make('hours_worked')
                    ->required()
                    ->numeric()
                    ->default(8.00),
                Forms\Components\TextInput::make('daily_rate_etb')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_cost_etb')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('foreman_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('work_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('worker_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('log_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hours_worked')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('daily_rate_etb')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_cost_etb')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ManageLaborLogs::route('/'),
        ];
    }
}
