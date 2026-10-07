<?php

namespace App\Filament\Widgets;

use App\Models\Estimation;
use App\Models\LaborLog;
use App\Models\Material;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CostOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalContracts = Project::sum('contract_value');
        $totalEstimated = Estimation::where('status', 'approved')->sum('total_etb');
        $totalLaborCost = LaborLog::sum('total_cost_etb');
        $activeProjects = Project::where('status', 'active')->count();

        return [
            Stat::make('Active Projects', $activeProjects)
                ->description('All sites operational')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->chart([2, 2, 3, 3, 4, 3, $activeProjects])
                ->color('success'),

            Stat::make('Total Portfolio Value', 'ብር ' . number_format($totalContracts / 1000000, 1) . 'M ETB')
                ->description('3 major enterprise contracts')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([25, 48, 80, 110, 130, 144.5])
                ->color('warning'),

            Stat::make('Approved Estimations', 'ብር ' . number_format($totalEstimated / 1000000, 1) . 'M ETB')
                ->description('+18.4% BOQ approval rate')
                ->descriptionIcon('heroicon-m-calculator')
                ->chart([15, 30, 45, 68, 92, 116])
                ->color('primary'),

            Stat::make('Recorded Field Labor', 'ብር ' . number_format($totalLaborCost, 0))
                ->description('From daily foreman logs')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([12000, 24000, 31000, 42000, 56000, $totalLaborCost])
                ->color('info'),
        ];
    }
}
