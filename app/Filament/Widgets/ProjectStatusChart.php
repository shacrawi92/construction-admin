<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;

class ProjectStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Projects by Status';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $draft = Project::where('status', 'draft')->count();
        $active = Project::where('status', 'active')->count();
        $onHold = Project::where('status', 'on_hold')->count();
        $completed = Project::where('status', 'completed')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Projects',
                    'data' => [$draft, $active, $onHold, $completed],
                    'backgroundColor' => ['#94a3b8', '#22c55e', '#f59e0b', '#3b82f6'],
                ],
            ],
            'labels' => ['Draft', 'Active', 'On Hold', 'Completed'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
