<?php

namespace App\Filament\Resources\BannerResource\Widgets;

use App\Models\Banner;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Banner', Banner::count())->description('Total data Banner'),
            Stat::make('Customers', Customer::count())->description('Total data Customer'),
            Stat::make('Service', Service::count())->description('Total data Service'),
            Stat::make('Specialist', Specialist::count())->description('Total data Specialist'),
            Stat::make('Transaction', 'Rp. ' . number_format(
                Transaction::whereMonth('dateFor', Carbon::now()->month)
                    ->whereYear('dateFor', Carbon::now()->year)
                    ->sum('subtotal'),
                0,
                ',',
                '.'
            ))
                ->description('Total Invoice Bulan Ini')
                ->chart([50000, 100000, 150000, 200000, 300000, 400000]),
        ];
    }
}
