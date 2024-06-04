<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $nomeApp = "FotoPlus";
        return view('layouts.guest', ['nomeApp' => $nomeApp]);
    }
}
