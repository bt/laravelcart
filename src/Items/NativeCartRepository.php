<?php

/**
 * Created by IntelliJ IDEA.
 * User: alanyu
 * Date: 24/11/2015
 * Time: 7:20 PM
 */
namespace Mnt\Cart\Items;

use Illuminate\Support\Collection;
use Mnt\Cart\Items\CartItemRepositoryInterface;

class NativeCartRepository extends Collection implements CartRepositoryInterface
{

    public function price($total = false)
    {

        if($total) {
            $price = $this->get('price') * $this->quantity();
        }
        else{
            $price = $this->get('price');
        }

        return $price;
    }

    public function quantity()
    {
        return $this->get('quantity');
    }

}