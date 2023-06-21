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
        if (!file_exists('files/covers/' . $this->work->id . '.png')) {

            //dd($work);
            if ($this->work->author == null) {
                $author = "";
            } else {
                $author = $this->work->author[0]['name'];
            }

            if ($this->work->publisher == null) {
                $publisher = "";
            } else {
                $publisher = $this->work->publisher[0]['name'];
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
            $path = 'files/covers/' . $this->work->id . '.png';
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64_cover = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $headers[] = header('Content-Type: image/png');
        }

        return view('components.cover', ['base64_cover' => $base64_cover]);
    }


}