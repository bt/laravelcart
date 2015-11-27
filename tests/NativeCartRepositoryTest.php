<?php
/**
 * Created by IntelliJ IDEA.
 * User: alanyu
 * Date: 25/11/2015
 * Time: 2:15 PM
 */

namespace Mnt\Cart\tests;

use Mnt\Cart\Items\NativeCartRepository;
use Mockery as m;
use PHPUnit_Framework_TestCase;


class NativeCartRepositoryTest extends PHPUnit_Framework_TestCase
{
    /*
     * Close mockery
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    public function testQuantity()
    {
        $cartItem = new NativeCartRepository(['quantity' => 100]);

        $this->assertEquals(100, $cartItem->quantity());

    }

    public function testPrice()
    {
        $cartItem = new NativeCartRepository(['price' => 100, 'quantity' => 2]);

        $this->assertEquals(100, $cartItem->price());
        $this->assertEquals(200, $cartItem->price(true));
    }

}