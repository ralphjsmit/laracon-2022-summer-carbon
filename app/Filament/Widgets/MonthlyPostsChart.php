<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\BarChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class MonthlyPostsChart extends BarChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getFilters(): ?array
    {
        $oldestPublishedAt = Post::query()
            // Exclude draft posts, because they have no date.
            ->whereNotNull('published_at')
            ->orderBy('published_at')
            ->value('published_at')
            ->startOfMonth();

        $months = [];

        while ($oldestPublishedAt->lte(now())) {
            $months[$oldestPublishedAt->format('Y-m')] = $oldestPublishedAt->format('M Y');

            $oldestPublishedAt->addMonth();
        }

        $months = array_reverse($months, preserve_keys: true);

        return $months;
    }

    protected function getData(): array
    {
        $filter = $this->filter;

        $data = Trend::model(Post::class)
            ->between(
                start: $filter ? Carbon::parse($filter)->startOfMonth() : now()->subMonths(9)->startOfYear(),
                end: $filter ? Carbon::parse($filter)->endOfMonth() : now()->endOfYear(),
            )
            ->perMonth()
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
