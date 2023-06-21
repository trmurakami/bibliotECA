<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PaginationPeople extends Component
{
    public $people;
    /**
     * Create a new component instance.
     */
    public function __construct($people)
    {
        $this->people = $people;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.paginationPeople');
    }
}