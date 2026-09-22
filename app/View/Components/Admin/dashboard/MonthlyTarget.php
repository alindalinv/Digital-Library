<?php

namespace App\View\Components\Admin\dashboard;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MonthlyTarget extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.admin.ecommerce.monthly-target');
    }
}




