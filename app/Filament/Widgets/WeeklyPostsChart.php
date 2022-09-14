<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\BarChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class WeeklyPostsChart extends BarChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getFilters(): ?array
    {
        return [];
    }

    protected function getData(): array
    {
        $filter = $this->filter ?? '';

        $year = Str::before($filter, '-');
        $week = Str::after($filter, '-');

        $data = Trend::model(Post::class)
            ->between(
                start: $filter ? Carbon::now()->setIsoDate($year, $week)->startOfWeek() : now()->subMonths(9)->startOfYear(),
                end: $filter ? Carbon::now()->setIsoDate($year, $week)->endOfWeek() : now()->endOfYear(),
            )
            ->perWeek()
            ->dateColumn('published_at')
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Blog posts',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(101, 163, 13, 1)',
                    'hoverBackgroundColor' => 'rgba(77, 124, 15, 1)',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
}
