<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use TeamTNT\TNTSearch\Classifier\TNTClassifier;

class StringClassifier
{
    protected $tnt;

    public function __construct()
    {
        $this->tnt = new TNTClassifier;
    }

    public function train(array $strings, array $labels)
    {
        if (file_exists('classifier.cls')) {
            $this->tnt->load('classifier.cls');
        }
        foreach ($strings as $index => $string) {
            $this->tnt->learn($string,$labels[$index]);
        }
        $this->tnt->save('classifier.cls');
    }

    public function predict($string)
    {
        $this->tnt->load('classifier.cls');
        $results = $this->tnt->predict($string);
        return $results;
    }
}