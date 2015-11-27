<?php

/**
 * Created by IntelliJ IDEA.
 * User: alanyu
 * Date: 24/11/2015
 * Time: 3:50 PM
 */

namespace Mnt\Cart;

use Illuminate\Contracts\Events\Dispatcher;
use Cartalyst\Support\Traits\RepositoryTrait;
use Cartalyst\Support\Traits\EventTrait;
use Mnt\Cart\Sessions\SessionRepositoryInterface;
use InvalidArgumentException;

class Cart
{

    use RepositoryTrait, EventTrait;

    protected $session;

    protected $model = 'Mnt\Cart\Items\NativeCartRepository';

    public function __construct(SessionRepositoryInterface $session, Dispatcher $dispatcher, $model = null)
    {
        $this->session = $session;

        $this->dispatcher = $dispatcher;

        if(isset($model)){
            $this->model = $model;
        }

    }

    public function addOrUpdateItem($item)
    {

        //check if the item has been given an id and quantity value
        $this->checkItem($item);

        $itemInSession = $this->session->get($item['id']);

        //check if this key already is in the session
        if($itemInSession !== []){
            $item['quantity'] += $itemInSession['quantity'];
            $this->fireEvent('cart.item.updated', [$item]);
            return $this->createModel($this->session->update([$item]));
        }

        $this->fireEvent('cart.item.added', [$item]);
        return $this->createModel($this->session->add($item));
    }

    public function updateItem($item)
    {
        $this->checkItem($item);

        $this->fireEvent('cart.item.updated', [$item]);

        return $this->createModel($this->session->update([$item]));
    }

    public function removeItem($item)
    {
        $this->checkItem($item);

        $this->fireEvent('cart.item.removed', [$item]);

        return $this->createModel($this->session->remove([$item['id']]));
    }

    public function getAllItems()
    {
        return $this->createModel($this->session->all());
    }

    public function getItem($id)
    {
        return $this->createModel($this->session->get($id));
    }

    public function emptyCart()
    {
        $this->session->clear();
    }

    public function mergeItems($items)
    {
        $items = $this->toArray($items);

        foreach($items as $item){
            $this->checkItem($item);

            $itemInSession = $this->session->get($item['id']);

            //check if this key already is in the session
            if($itemInSession !== []){
                $item['quantity'] += $itemInSession['quantity'];
                $this->session->update([$item]);
            }
            else{
                $this->session->add($item);
            }
        }

        return $this->createModel($this->session->all());
    }

    public function calculateSubtotal()
    {
        $items = $this->getAllItems();
        $total = 0;

        foreach($items as $item){
            $item = $this->createModel($item);
            $total += $item->price(true);
        }

        return $total;
    }

    public function numberOfItems(){
        $items = $this->getAllItems();

        return count($items);
    }


    protected function checkItem($item){
        if(!array_key_exists('id', $item)){
            throw new InvalidArgumentException('item must contain the key: id');
        }

        if(!array_key_exists('quantity', $item)){
            throw new InvalidArgumentException('item must contain the key: quantity');
        }
    }
}