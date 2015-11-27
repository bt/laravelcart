<?php
/**
 * Created by IntelliJ IDEA.
 * User: alanyu
 * Date: 24/11/2015
 * Time: 12:59 PM
 */

namespace Mnt\Cart\tests;

use Mnt\Cart\Sessions\IlluminateSession;
use Mockery as m;
use PHPUnit_Framework_TestCase;

class IlluminateSessionTest extends PHPUnit_Framework_TestCase
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

    public function testPut()
    {
        $store = m::mock('Illuminate\Session\Store');
        $session = new IlluminateSession($store, 'foo');
        $store->shouldReceive('put')->with('foo', 'bar');
        $session->put('bar');
    }

    public function testGet()
    {
        $store = m::mock('Illuminate\Session\Store');
        $session = new IlluminateSession($store, 'foo');
        $store->shouldReceive('get')->with('foo')->once()->andReturn('bar');
        $this->assertEquals('bar' , $session->get());
    }

    public function testForget()
    {
        $store = m::mock('Illuminate\Session\Store');
        $session = new IlluminateSession($store, 'foo');
        $store->shouldReceive('forget')->with('foo')->once();
        $session->forget();
    }


}