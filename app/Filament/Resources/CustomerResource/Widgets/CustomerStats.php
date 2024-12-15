<?php

namespace App\Filament\Resources\CustomerResource\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Customers', Customer::count())->description('Total data Customer')->chart([1, 10, 20, 30, 40, 50, 100])
        ];
    }
}
