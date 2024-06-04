<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $nomeApp = "FotoPlus";
        return view('layouts.app', ['nomeApp' => $nomeApp]);
    }
}
