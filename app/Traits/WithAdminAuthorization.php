<?php

namespace App\Traits;

trait WithAdminAuthorization
{
    public function mountWithAuthorization()
    {
        dd('ciao');
        \Gate::denies('admin',403);
    }
}
