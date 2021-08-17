<?php

namespace App\Http\Livewire;

use App\Models\HomeSlider;
use App\Models\Product;
use Livewire\Component;

class HomeComponent extends Component
{
    public function render()
    {
        $sliders = HomeSlider::all();
        $lproducts = Product::orderby('created_at','DESC')->get()->take(8);
        return view('livewire.home-component',['sliders' => $sliders, 'lproducts' =>$lproducts])->layout('layouts.base');
    }
}
