<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Pagination extends Component
{
    public $works;
    public $search;
    /**
     * Create a new component instance.
     */
    public function __construct($works, $search)
    {
        $this->works = $works;
        $this->search = $search;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.pagination');
    }
}