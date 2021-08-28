<?php

namespace App\Http\Livewire;

use App\Models\Coupon;
use Livewire\Component;
use Cart;
class CartComponent extends Component
{
    public $haveCouponCode;
    public $couponCode;
    public $discount;
    public $subTotalAfterDiscount;
    public $taxAfterDiscount;
    public $totalAfterDiscount;

    public function increaseQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty +1;
        Cart::instance('cart')->update($rowId,$qty);
        $this->emitTo('cart-count-component','refreshComponent');
    }
    public function decreaseQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty -1;
        Cart::instance('cart')->update($rowId,$qty);
        $this->emitTo('cart-count-component','refreshComponent');
    }
    public function destroy($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        $this->emitTo('cart-count-component','refreshComponent');
        session()->flash('success_message','Item has been removed');
    }
    public function destroyAll(){
        Cart::instance('cart')->destroy();
        $this->emitTo('cart-count-component','refreshComponent');
        session()->flash('success_message','All item has been removed');
    }

    public function applyCouponCode()
    {

        $coupon = Coupon::where('code', $this->couponCode)->where('cart_value','<=',Cart::instance('cart')->subtotal())->first();
        //dd($coupon);
        if (!$coupon)
        {
            session()->flash('coupon_message','Coupon code is invalid');
            return;
        }
        session()->put('coupon',[
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value
        ]);
    }

    public function calculateDiscount()
    {
    if (session()->has('coupon'))
    {
        if (session()->get('coupon')['type']== 'fixed')
        {
            $this->discount = session()->get('coupon')['value'];
        }
        else{
            $this->discount = (Cart::instance('cart')->subtotal() * session()->get('coupon')['value']/100);
        }
        $this->subTotalAfterDiscount = Cart::instance('cart')->subtotal() - $this->discount;
        $this->taxAfterDiscount = ($this->subTotalAfterDiscount * config('cart.tax'))/100;
        $this->totalAfterDiscount = $this->subTotalAfterDiscount + $this->taxAfterDiscount;
    }
    }
    public function removeDiscount()
    {
        session()->forget('coupon');
    }
    public function render()
    {
        if(session()->has('coupon'))
        {
          if(Cart::instance('cart')->subTotal() < session()->get('coupon')['cart_value'])
          {
              session()->forget('coupon');
          }
          else{
              $this->calculateDiscount();
          }
        }
        return view('livewire.cart-component')->layout('layouts.base');
    }
}
