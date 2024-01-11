<?php

namespace App\Http\Livewire\Profile;

use Auth;
use Livewire\Component;

class Logout extends Component
{
    public function render()
    {
        return view('livewire.profile.logout');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        $this->redirect(route('login'));
    }
}
