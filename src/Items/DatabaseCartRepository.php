<?php
/**
 * Created by IntelliJ IDEA.
 * User: alanyu
 * Date: 27/11/2015
 * Time: 3:18 PM
 */

namespace Mnt\Cart\Items;

use Illuminate\Support\Collection;
use Mnt\Cart\Items\CartRepositoryInterface;

class DatabaseCartRepository extends Collection implements CartRepositoryInterface
{
    public function price($total = false)
    {

        $products = \App::make('Repositories\Interfaces\ProductRepository');

        $product = $products->findOrFail($this->get('id'));

        if($total) {
            $price = $product->price_exgst * $this->quantity();
        }
        else{
            $price = $product->price_exgst;
        }
        return $price;
    }

    public function quantity()
    {
        return $this->get('quantity');
    }


}