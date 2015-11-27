<?php
/**
 * Created by IntelliJ IDEA.
 * User: alanyu
 * Date: 25/11/2015
 * Time: 3:09 PM
 */

namespace Mnt\Cart\tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Mnt\Cart\Sessions\SessionRepository;

class SessionRepositoryTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testAdd()
    {
        $session = m::mock('Mnt\Cart\Sessions\SessionInterface');
        $sessionRepository = new SessionRepository($session);

        $session->shouldReceive('get')->times(2)->andReturn([], ['ab' => ['item' => 'hello world']]);
        $session->shouldReceive('put')->with(['abc' => ['item' => 'hello world']]);
        $session->shouldReceive('put')->with(['ab' => ['item' => 'hello world'], 'dce' => ['item' => 'hello world2']]);

        $this->assertEquals([['id' => 'abc', 'item' => 'hello world']], $sessionRepository->add(['id' => 'abc', 'item' => 'hello world']));
        $this->assertEquals([['id' => 'ab', 'item' => 'hello world'], ['id' => 'dce', 'item' => 'hello world2']], $sessionRepository->add(['id' => 'dce', 'item' => 'hello world2']));
    }

    public function testRemove()
    {
        $session = m::mock('Mnt\Cart\Sessions\SessionInterface');
        $sessionRepository = new SessionRepository($session);

        $session->shouldReceive('get')->once()->andReturn(['a' => ['item' => 'h1'], 'b' => ['item' => 'h2'], 'c' => ['item' => 'h3']]);
        $session->shouldReceive('put')->once()->with(['c' => ['item' => 'h3']]);

        $this->assertEquals([['id' => 'c', 'item' => 'h3']], $sessionRepository->remove(['a', 'b']));
    }

    public function testAll()
    {
        $session = m::mock('Mnt\Cart\Sessions\SessionInterface');
        $sessionRepository = new SessionRepository($session);

        $session->shouldReceive('get')->times(2)->andReturn(null, ['a' => ['item' => 'h1'], 'b' => ['item' => 'h2']]);
        $this->assertEquals([], $sessionRepository->all());
        $this->assertEquals([['id' => 'a', 'item' => 'h1'], ['id' => 'b', 'item' => 'h2']], $sessionRepository->all());
    }

    public function testGet()
    {
        $session = m::mock('Mnt\Cart\Sessions\SessionInterface');
        $sessionRepository = new SessionRepository($session);

        $session->shouldReceive('get')->times(2)->andReturn(['a' => ['item' => 'h1', 'price' => 100], 'b' => ['item' => 'h2', 'price' => 200], 'c' => ['item' => 'h10', 'price' => 1000]], null);

        $this->assertEquals(['id' => 'b', 'item' => 'h2', 'price' => 200], $sessionRepository->get('b'));
        $this->assertEquals([], $sessionRepository->get('b'));
    }

    public function testClear()
    {
        $session = m::mock('Mnt\Cart\Sessions\SessionInterface');
        $sessionRepository = new SessionRepository($session);

        $session->shouldReceive('put')->once()->with([]);
        $sessionRepository->clear();

    }

    public function testUpdate()
    {
        $session = m::mock('Mnt\Cart\Sessions\SessionInterface');
        $sessionRepository = new SessionRepository($session);

        $session->shouldReceive('get')->once()->andReturn(['a' => ['item' => 'h1', 'price' => 100], 'b' => ['item' => 'h2', 'price' => 200], 'c' => ['item' => 'h10', 'price' => 1000]]);
        $session->shouldReceive('put')->once()->with(['a' => ['item' => 'h5', 'price' => 300], 'b' => ['item' => 'h2', 'price' => 200], 'c' => ['item' => 'h10', 'price' => 20]]);

        $this->assertEquals([['id' => 'a', 'item' => 'h5', 'price' => 300], ['id' => 'b', 'item' => 'h2', 'price' => 200], ['id' => 'c', 'item' => 'h10', 'price' => 20]], $sessionRepository->update([['id' => 'a', 'item' => 'h5', 'price' => 300], ['id' => 'c', 'price' => 20]]));
    }

}