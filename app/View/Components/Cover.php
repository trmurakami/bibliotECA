<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Work;
use Medlib\BookCover\BookCover;

class Cover extends Component {
    public $work;
    /**
     * Create a new component instance.
     */
    public function __construct($work)
    {
        $this->work = $work;
    }
    public function render(): View|Closure|string
    {
        if ($this->work->base64Image == null) {
            if ($this->work->author == null) {
                $author = "";
            } else {
                $author = $this->work->author[0]['name'];
            }

            if ($this->work->publisher == null) {
                $publisher = "";
            } else {
                $publisher = $this->work->publisher;
            }

            $cover = new BookCover();
            $cover->setTitle($this->work->name)
                ->setSubtitle($this->work->subtitle)
                ->setCreators($author)
                ->setEdition($this->work->edition)
                ->setPublisher($publisher)
                ->setDatePublished($this->work->datePublished)
                ->randomizeBackgroundColor();
            $base64_cover = $cover->getImageBase64();
        } else {
            $base64_cover = $this->work->base64Image;
        }
        return view('components.cover', ['base64_cover' => $base64_cover]);
    }


}