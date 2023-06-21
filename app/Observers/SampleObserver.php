<?php

namespace App\Observers;

use App\Models\Sample;

class SampleObserver
{
    public function creating(Sample $sample)
    {
        if(auth()->check()) {
            $sample->created_by = auth()->user()->id;
        }
    }

    public function updating(Sample $sample)
    {
        if(auth()->check()) {
            $sample->updated_by = auth()->user()->id;
        }
    }
}
