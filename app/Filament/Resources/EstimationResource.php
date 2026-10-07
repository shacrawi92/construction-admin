<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstimationResource\Pages;
use App\Models\Estimation;
use App\Models\Project;
use App\Models\WorkType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EstimationResource extends Resource
{
    protected static ?string $model = Estimation::class;
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationGroup = 'Project Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Estimation Details')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'approved' => 'Approved',
                                'revised' => 'Revised',
                            ])
                            ->default('draft')
                            ->required(),
                        Forms\Components\TextInput::make('contingency_pct')
                            ->label('Contingency (%)')
                            ->numeric()
                            ->default(5.0)
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Line Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Forms\Components\Select::make('work_type_id')
                                    ->label('Work Type')
                                    ->options(WorkType::all()->pluck('name_en', 'id'))
                                    ->searchable(),
                                Forms\Components\TextInput::make('description')
                                    ->required(),
                                Forms\Components\TextInput::make('unit')
                                    ->placeholder('m², m³, kg, pcs')
                                    ->required(),
                                Forms\Components\TextInput::make('qty')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Forms\Set $set, Forms\Get $get) => 
                                        $set('total_etb', round((float)$state * (float)$get('unit_price_etb'), 2))),
                                Forms\Components\TextInput::make('unit_price_etb')
                                    ->label('Unit Price (ETB)')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Forms\Set $set, Forms\Get $get) => 
                                        $set('total_etb', round((float)$state * (float)$get('qty'), 2))),
                                Forms\Components\TextInput::make('total_etb')
                                    ->label('Total (ETB)')
                                    ->numeric()
                                    ->readOnly(),
                            ])
                            ->columns(6)
                            ->defaultItems(1)
                            ->addActionLabel('Add Estimation Item'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtotal_etb')
                    ->label('Subtotal')
                    ->money('ETB')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contingency_pct')
                    ->label('Contingency')
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('total_etb')
                    ->label('Total (ETB)')
                    ->money('ETB')
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'approved' => 'success',
                        'revised' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'approved' => 'Approved',
                        'revised' => 'Revised',
                    ]),
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
            'index' => Pages\ManageEstimations::route('/'),
        ];
    }
}
