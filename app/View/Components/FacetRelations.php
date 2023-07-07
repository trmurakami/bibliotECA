<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Thing;
use App\Models\About;

class FacetRelations extends Component
{

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $field,
        public string $fieldName,
        public request $request
    ){}

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
        $about = $this->request->about;
        $inSupportOf = $this->request->inSupportOf;
        $sourceOrganization = $this->request->sourceOrganization;
        $isbn = $this->request->isbn;
        $doi = $this->request->doi;

        if ($this->field == 'authors') {
            $q = Thing::query();
        } elseif ($this->field == 'abouts') {
            $q = About::query();
        }
        
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
            if (!empty($inSupportOf)) {
                $q->where('inSupportOf', $inSupportOf);
            }
            if (!empty($sourceOrganization)) {
                $q->where('sourceOrganization', $sourceOrganization);
            }
            if (!empty($isbn)) {
                $q->where('isbn', $isbn);
            }
            if (!empty($doi)) {
                $q->where('doi', $doi);
            }
            if (!empty($author)) {
                $q->whereHas($this->field, function ($q) use ($author) {
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
            if (!empty($inSupportOf)) {
                $q->where('inSupportOf', $inSupportOf);
            }
            if (!empty($sourceOrganization)) {
                $q->where('sourceOrganization', $sourceOrganization);
            }
            if (!empty($isbn)) {
                $q->where('isbn', $isbn);
            }
            if (!empty($doi)) {
                $q->where('doi', $doi);
            }
            if (!empty($author)) {
                $q->whereHas($this->field, function ($q) use ($author) {
                    $q->where('name', 'LIKE', '%' . $author . '%');
                });
            }
        }])->get();

        $q->orderByDesc('works_count');
        $facets = $q->limit(10)->get();


        
        return view('components.facet-relations', compact('facets'));
    }
}