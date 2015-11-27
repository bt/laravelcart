<?php

/**
 * Created by IntelliJ IDEA.
 * User: alanyu
 * Date: 24/11/2015
 * Time: 11:44 PM
 */
namespace Mnt\Cart\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Cart extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'cart';
    }
    
}