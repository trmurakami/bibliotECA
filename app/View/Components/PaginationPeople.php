<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PaginationThings extends Component
{
    public $things;
    /**
     * Create a new component instance.
     */
    public function __construct($things)
    {
        $this->things = $things;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.paginationThings');
    }
}