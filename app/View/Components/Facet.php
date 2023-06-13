<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Http\Request;
use App\Models\Work;
use Illuminate\Support\Facades\DB;

class Facet extends Component
{
    public $field;
    public $fieldName;
    public $request;
    /**
     * Create a new component instance.
     */
    public function __construct(Request $request, $field, $fieldName)
    {
        $this->request = $request;
        $this->field = $field;
        $this->fieldName = $fieldName;        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $query = Work::select('' . $this->field . ' as field', DB::raw('count(*) as count'));
        if ($this->request->search) {
            $query->where('name', 'like', '%' . $this->request->search . '%');
        }

        if ($this->request->type) {
            $query->where('type', $this->request->type);
        }
        $query->groupBy($this->field)->orderByDesc('count')->orderByDesc($this->field)->limit(10);
        $result = $query->get();
        $facets[0]['field'] = $this->field;
        $facets[0]['fieldName'] = $this->fieldName;
        $facets[0]['values'] = $result->toArray();
        return view('components.facet', compact('facets'));
    }
}