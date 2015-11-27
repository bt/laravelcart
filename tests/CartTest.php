<?php
/**
 * Created by IntelliJ IDEA.
 * User: alanyu
 * Date: 26/11/2015
 * Time: 2:52 PM
 */

namespace Mnt\Cart\tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Cartalyst\Support\Traits\RepositoryTrait;
use Mnt\Cart\Items\NativeCartRepository;
use Mnt\Cart\Cart;

class CartTest extends PHPUnit_Framework_TestCase
{

    use RepositoryTrait;

    protected $model = 'Mnt\Cart\Items\NativeCartRepository';

    /*
     * Close mockery
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    public function testAddOrUpdate(){
        $session = m::mock('Mnt\Cart\Sessions\SessionRepositoryInterface');
        $dispatcher = m::mock('Illuminate\Contracts\Events\Dispatcher');

        $cart = new Cart($session, $dispatcher);

        $session->shouldReceive('get')->andReturn([], ['id' => 1, 'quantity' => 5]);
        $session->shouldReceive('add')->with(['id' => 1, 'quantity' => 5])->andReturn(['id' => 1, 'quantity' => 5]);
        $session->shouldReceive('update')->with([['id' => 1, 'quantity' => 10]])->andReturn(['id' => 1, 'quantity' => 10]);


        $dispatcher->shouldReceive('fire')->withArgs(array('cart.item.added', [['id' => 1, 'quantity' => 5]]));
        $dispatcher->shouldReceive('fire')->withArgs(array('cart.item.updated', [['id' => 1, 'quantity' => 10]]));


        $this->assertEquals($this->createModel(['id' => 1, 'quantity' => 5]), $cart->addOrUpdateItem(['id' => 1, 'quantity' => 5]));
        $this->assertEquals($this->createModel(['id' => 1, 'quantity' => 10]), $cart->addOrUpdateItem(['id' => 1, 'quantity' => 5]));
    }


    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage item must contain the key: id
     */
    public function testAddOrUpdateExceptionId(){
        $session = m::mock('Mnt\Cart\Sessions\SessionRepositoryInterface');
        $dispatcher = m::mock('Illuminate\Contracts\Events\Dispatcher');

        $cart = new Cart($session, $dispatcher);

        $cart->addOrUpdateItem(['quantity' => 5]);

    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage item must contain the key: quantity
     */
    public function testAddOrUpdateExceptionQuantity(){
        $session = m::mock('Mnt\Cart\Sessions\SessionRepositoryInterface');
        $dispatcher = m::mock('Illuminate\Contracts\Events\Dispatcher');

        $cart = new Cart($session, $dispatcher);

        $cart->addOrUpdateItem(['id' => 1]);
    }

    public function testEmptyCart(){
        $session = m::mock('Mnt\Cart\Sessions\SessionRepositoryInterface');
        $dispatcher = m::mock('Illuminate\Contracts\Events\Dispatcher');

        $cart = new Cart($session, $dispatcher);
        $session->shouldReceive('clear')->once();

        $cart->emptyCart();

    }

    public function testGetItem(){
        $session = m::mock('Mnt\Cart\Sessions\SessionRepositoryInterface');
        $dispatcher = m::mock('Illuminate\Contracts\Events\Dispatcher');

        $cart = new Cart($session, $dispatcher);
        $session->shouldReceive('get')->with(1)->andReturn(['id' => 1]);
        $session->shouldReceive('get')->with(3)->andReturn([]);

        $this->assertEquals($this->createModel(['id' => 1]), $cart->getItem(1));
        $this->assertEquals($this->createModel([]), $cart->getItem(3));

    }

    public function testGetAllItems(){
        $session = m::mock('Mnt\Cart\Sessions\SessionRepositoryInterface');
        $dispatcher = m::mock('Illuminate\Contracts\Events\Dispatcher');

        $cart = new Cart($session, $dispatcher);
        $session->shouldReceive('all')->andReturn([['id' => 1], ['id' => 2]]);

        $this->assertEquals($this->createModel([['id' => 1], ['id' => 2]]), $cart->getAllItems());

    }

    public function testRemoveItem(){
        $session = m::mock('Mnt\Cart\Sessions\SessionRepositoryInterface');
        $dispatcher = m::mock('Illuminate\Contracts\Events\Dispatcher');

        $cart = new Cart($session, $dispatcher);
        $session->shouldReceive('remove')->with([1])->andReturn([['id' => 3, 'quantity' => 5]]);

        $dispatcher->shouldReceive('fire')->once()->withArgs(array('cart.item.removed', [['id' => 1, 'quantity' => 5]]));

        $this->assertEquals($this->createModel([['id' => 3, 'quantity' => 5]]), $cart->removeItem(['id' => 1, 'quantity' => 5]));

    }

    public function testUpdateItem(){
        $session = m::mock('Mnt\Cart\Sessions\SessionRepositoryInterface');
        $dispatcher = m::mock('Illuminate\Contracts\Events\Dispatcher');

        $cart = new Cart($session, $dispatcher);
        $session->shouldReceive('update')->with([['id' => 5, 'quantity' => 10]])->andReturn([['id' => 5, 'quantity' => 10]]);

        $dispatcher->shouldReceive('fire')->once()->withArgs(array('cart.item.updated', [['id' => 5, 'quantity' => 10]]));

        $this->assertEquals($this->createModel([['id' => 5, 'quantity' => 10]]), $cart->updateItem(['id' => 5, 'quantity' => 10]));

    }

    public function testMergeItems(){
        $session = m::mock('Mnt\Cart\Sessions\SessionRepositoryInterface');
        $dispatcher = m::mock('Illuminate\Contracts\Events\Dispatcher');

        $cart = new Cart($session, $dispatcher);

        $session->shouldReceive('get')->withAnyArgs()->andReturn([], ['id' => 2, 'quantity' => 1]);
        $session->shouldReceive('add')->with(['id' => 1, 'quantity' => 1]);
        $session->shouldReceive('update')->with([['id' => 2, 'quantity' => 2]]);
        $session->shouldReceive('all')->andReturn([['id' => 1, 'quantity' => 1], ['id' => 2, 'quantity' => 2]]);

        $this->assertEquals($this->createModel([['id' => 1, 'quantity' => 1], ['id' => 2, 'quantity' => 2]]), $cart->mergeItems([['id' => 1, 'quantity' => 1], ['id' => 2, 'quantity' => 1]]));

    }

    public function testCalculateSubtotal(){
        $session = m::mock('Mnt\Cart\Sessions\SessionRepositoryInterface');
        $dispatcher = m::mock('Illuminate\Contracts\Events\Dispatcher');

        $cart = new Cart($session, $dispatcher);

        $session->shouldReceive('all')->andReturn([['id' => 1, 'quantity' => 1, 'price' => 100], ['id' => 2, 'quantity' => 2, 'price' => 50]]);

        $this->assertEquals(200, $cart->calculateSubtotal());

    }

    public function testNumberOfItems(){
        $session = m::mock('Mnt\Cart\Sessions\SessionRepositoryInterface');
        $dispatcher = m::mock('Illuminate\Contracts\Events\Dispatcher');

        $cart = new Cart($session, $dispatcher);

        $session->shouldReceive('all')->andReturn([['id' => 1, 'quantity' => 1, 'price' => 100], ['id' => 2, 'quantity' => 2, 'price' => 50]]);

        $this->assertEquals(2, $cart->numberOfItems());

    }

}