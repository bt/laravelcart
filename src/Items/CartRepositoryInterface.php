<?php

/**
 * Created by IntelliJ IDEA.
 * User: alanyu
 * Date: 24/11/2015
 * Time: 7:19 PM
 */
namespace Mnt\Cart\Items;


interface CartRepositoryInterface
{

    public function price($total);

    public function quantity();

    public function toArray();

}