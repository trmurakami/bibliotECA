<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Thing;
use Illuminate\Support\Facades\DB;

class FacetAuthors extends Component
{
    public $field;
    public $fieldName;
    public $request;
    /**
     * Create a new component instance.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $type = $this->request->type;
        $name = $this->request->name;
        $datePublished = $this->request->datePublished;
        $inLanguage = $this->request->inLanguage;
        $issn = $this->request->issn;
        $author = $this->request->author;
        $q = Thing::query();
        
        $q->whereHas('works', function ($q) use ($type, $name, $datePublished, $inLanguage, $issn, $author) {
            if (!empty($type)) {
                $q->where('type', $type);
            }
            if (!empty($name)) {
                $q->where('name', 'LIKE', '%' . $name . '%');
            }
            if (!empty($datePublished)) {
                $q->where('datePublished', $datePublished);
            }
            if (!empty($inLanguage)) {
                $q->where('inLanguage', 'LIKE', '%' . $inLanguage . '%');
            }
            if (!empty($issn)) {
                $q->where('issn', $issn);
            }
            if (!empty($author)) {
                $q->whereHas('authors', function ($q) use ($author) {
                    $q->where('name', 'LIKE', '%' . $author . '%');
                });
            }
        });

        $q->withCount(['works' => function ($q) use ($type, $name, $datePublished, $inLanguage, $issn, $author) {
            if (!empty($type)) {
                $q->where('type', $type);
            }
            if (!empty($name)) {
                $q->where('name', 'LIKE', '%' . $name . '%');
            }
            if (!empty($datePublished)) {
                $q->where('datePublished', $datePublished);
            }
            if (!empty($inLanguage)) {
                $q->where('inLanguage', 'LIKE', '%' . $inLanguage . '%');
            }
            if (!empty($issn)) {
                $q->where('issn', $issn);
            }
            if (!empty($author)) {
                $q->whereHas('authors', function ($q) use ($author) {
                    $q->where('name', 'LIKE', '%' . $author . '%');
                });
            }
        }])->get();

        $q->orderByDesc('works_count');
        $facets = $q->limit(10)->get();

        //dd($facets);

        // $query = Work::select('' . $this->field . ' as field', DB::raw('count(*) as count'));
        // if ($this->request->name) {
        //     $query->where('name', 'like', '%' . $this->request->name . '%');
        // }

        // if ($this->request->type) {
        //     $query->where('type', $this->request->type);
        // }
        // if ($this->request->datePublished) {
        //     $query->where('datePublished', $this->request->datePublished);
        // }
        // if ($this->request->inLanguage) {
        //     $query->where('inLanguage', 'like', '%' .  $this->request->inLanguage . '%');
        // }
        // if ($this->request->issn) {
        //     $query->where('issn', $this->request->issn);
        // }
        // if ($this->request->author) {
        //     $search = $this->request->author;
        //     $query->whereHas('authors', function ($query) use ($search) {
        //         $query->where('name', 'like', '%' . $search . '%');
        //     });
        // }
        // if ($this->request->releasedEvent) {
        //     $query->where('releasedEvent', 'like', '%' . $this->request->releasedEvent . '%');
            
        // }
        // if ($this->request->isPartOf_name) {
        //     $querywhere('isPartOf_name', 'like', '%' . $this->request->isPartOf_name . '%');
            
        // }
        // if ($this->field == 'datePublished') {
        //     $query->groupBy($this->field)->orderByDesc('field')->limit(10);
        // } else {
        //     $query->groupBy($this->field)->orderByDesc('count')->orderByDesc($this->field)->limit(10);
        // }
        
        // $result = $query->get();
        // $facets[0]['field'] = $this->field;
        // $facets[0]['fieldName'] = $this->fieldName;
        // $facets[0]['values'] = $result->toArray();
        // $facets[0]['request'][0]['field'] = "name";
        // $facets[0]['request'][0]['value'] = $this->request->name;
        // if ($this->request->type) {
        //     $facets[0]['request'][1]['field'] = "type";
        //     $facets[0]['request'][1]['value'] = $this->request->type;
        // }
        // if ($this->request->datePublished) {
        //     $facets[0]['request'][2]['field'] = "datePublished";
        //     $facets[0]['request'][2]['value'] = $this->request->datePublished;
        // }
        // if ($this->request->author) {
        //     $facets[0]['request'][3]['field'] = "author";
        //     $facets[0]['request'][3]['value'] = $this->request->author;
        // }
        // if ($this->request->releasedEvent) {
        //     $facets[0]['request'][4]['field'] = "releasedEvent";
        //     $facets[0]['request'][4]['value'] = $this->request->releasedEvent;
        // }
        // if ($this->request->isPartOf_name) {
        //     $facets[0]['request'][5]['field'] = "isPartOf_name";
        //     $facets[0]['request'][5]['value'] = $this->request->isPartOf_name;
        // }
        return view('components.facetAuthors', compact('facets'));
    }
}