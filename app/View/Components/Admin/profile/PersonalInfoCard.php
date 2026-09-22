<?php

namespace App\View\Components\Admin\profile;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PersonalInfoCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public User $user)
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.profile.personal-info-card');
    }
}
