<?php

namespace App\Observers;

class LarvaeObserver
{
    public function creating($params)
    {
        if(auth()->check())
        {
            $params->created_by = auth()->user()->id;
        }
    }

    public function updating($params)
    {
        if(auth()->check())
        {
            $params->updated_by = auth()->user()->id;
        }
    }
}
