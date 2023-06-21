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
        if ($this->request->name) {
            $query->where('name', 'like', '%' . $this->request->name . '%');
        }

        if ($this->request->type) {
            $query->where('type', $this->request->type);
        }
        if ($this->request->datePublished) {
            $query->where('datePublished', $this->request->datePublished);
        }
        if ($this->request->inLanguage) {
            $query->where('inLanguage', 'like', '%' .  $this->request->inLanguage . '%');
        }
        if ($this->request->issn) {
            $query->where('issn', $this->request->issn);
        }
        if ($this->request->author) {
            $search = $this->request->author;
            $query->whereHas('authors', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }
        if ($this->request->releasedEvent) {
            $query->where('releasedEvent', 'like', '%' . $this->request->releasedEvent . '%');
            
        }
        if ($this->request->isPartOf_name) {
            $querywhere('isPartOf_name', 'like', '%' . $this->request->isPartOf_name . '%');
            
        }
        if ($this->field == 'datePublished') {
            $query->groupBy($this->field)->orderByDesc('field')->limit(10);
        } else {
            $query->groupBy($this->field)->orderByDesc('count')->orderByDesc($this->field)->limit(10);
        }
        
        $result = $query->get();
        $facets[0]['field'] = $this->field;
        $facets[0]['fieldName'] = $this->fieldName;
        $facets[0]['values'] = $result->toArray();
        $facets[0]['request'][0]['field'] = "name";
        $facets[0]['request'][0]['value'] = $this->request->name;
        if ($this->request->type) {
            $facets[0]['request'][1]['field'] = "type";
            $facets[0]['request'][1]['value'] = $this->request->type;
        }
        if ($this->request->datePublished) {
            $facets[0]['request'][2]['field'] = "datePublished";
            $facets[0]['request'][2]['value'] = $this->request->datePublished;
        }
        if ($this->request->author) {
            $facets[0]['request'][3]['field'] = "author";
            $facets[0]['request'][3]['value'] = $this->request->author;
        }
        if ($this->request->releasedEvent) {
            $facets[0]['request'][4]['field'] = "releasedEvent";
            $facets[0]['request'][4]['value'] = $this->request->releasedEvent;
        }
        if ($this->request->isPartOf_name) {
            $facets[0]['request'][5]['field'] = "isPartOf_name";
            $facets[0]['request'][5]['value'] = $this->request->isPartOf_name;
        }
        return view('components.facet', compact('facets'));
    }
}