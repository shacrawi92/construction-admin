<?php

namespace App\Filament\Widgets;

use App\Models\Material;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockAlertTable extends BaseWidget
{
    protected static ?string $heading = 'Low Stock Inventory Alerts';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Material::query()->whereColumn('stock_qty', '<=', 'reorder_level')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name_en')
                    ->label('Material Name')
                    ->weight('bold')
                    ->description(fn (Material $record): string => $record->name_am ?? ''),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('stock_qty')
                    ->label('Current Stock')
                    ->color('danger')
                    ->weight('bold')
                    ->suffix(fn (Material $record): string => ' ' . $record->unit),
                Tables\Columns\TextColumn::make('reorder_level')
                    ->label('Reorder Level')
                    ->suffix(fn (Material $record): string => ' ' . $record->unit),
                Tables\Columns\TextColumn::make('unit_cost_etb')
                    ->label('Unit Cost')
                    ->money('ETB'),
                Tables\Columns\TextColumn::make('supplier')
                    ->placeholder('Not assigned'),
            ]);
    }
}
