<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Layout extends Component
{
    public $username;
    public $title;
    public $nav;

    /**
     * Create a new component instance.
     */
    public function __construct($username = null, $title = null, $nav = null)
    {
        $this->username = $username;
        $this->title = $title;
        $this->nav = $nav;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout');
    }
}
