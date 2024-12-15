<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as FilamentDashboard;

class Home extends FilamentDashboard
{
    protected static ?string $title = 'בית';

    protected static ?string $navigationLabel = 'בית';

    protected ?string $heading;

    protected ?string $subheading;

    public function __construct()
    {
        $this->heading = 'מנוחת עולם';
        $this->subheading = 'ניהול תהליך הקבורה ביעילות ובנוחות';
    }
}