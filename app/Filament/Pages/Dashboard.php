<?php

namespace App\Filament\Pages;

use App\Filament\Resources\BannerResource\Widgets\StatsOverview;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    protected function getWidgets(): array
    {
        // return default widgets
        return [
            StatsOverview::class,
        ];
    }
}
