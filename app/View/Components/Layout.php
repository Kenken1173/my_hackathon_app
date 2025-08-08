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

    public bool $footerFlag;

    /**
     * Create a new component instance.
     */
    public function __construct($username = null, $title = null, $nav = null, bool $footerFlag = true)
    {
        $this->username = $username;
        $this->title = $title;
        $this->nav = $nav;
        $this->footerFlag = $footerFlag;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout');
    }
}
