<?php

namespace App\Filament\Widgets;

use App\Models\Budget;
use Filament\Widgets\ChartWidget;

class FinancialBurnRateChart extends ChartWidget
{
    protected static ?string $heading = 'Financial Budget Allocation vs. Realized Spend (ETB Millions)';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $categories = ['Labour', 'Materials', 'Equipment', 'Overhead', 'Contingency'];
        $allocated = [];
        $spent = [];

        foreach ($categories as $cat) {
            $allocated[] = round(Budget::where('category', $cat)->sum('allocated_etb') / 1000000, 2);
            $spent[]     = round(Budget::where('category', $cat)->sum('spent_etb') / 1000000, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Budget Allocated (M ETB)',
                    'data' => $allocated,
                    'backgroundColor' => 'rgba(249, 115, 22, 0.4)',
                    'borderColor' => '#f97316',
                    'borderRadius' => 6,
                ],
                [
                    'label' => 'Realized Spend (M ETB)',
                    'data' => $spent,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.6)',
                    'borderColor' => '#10b981',
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $categories,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
