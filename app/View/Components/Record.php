<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Work;

class Record extends Component
{
    public $work;
    /**
     * Create a new component instance.
     */
    public function __construct($work)
    {
        $this->work = $work;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.record');
    }
}