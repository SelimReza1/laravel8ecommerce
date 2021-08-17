<?php

namespace App\Http\Livewire;

use App\Models\HomeSlider;
use Livewire\Component;

class HomeComponent extends Component
{
    public function render()
    {
        $sliders = HomeSlider::all();
        return view('livewire.home-component',['sliders' => $sliders])->layout('layouts.base');
    }
}
