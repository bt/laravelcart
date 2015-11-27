<?php
/**
 * Created by IntelliJ IDEA.
 * User: alanyu
 * Date: 25/11/2015
 * Time: 2:35 PM
 */

namespace Mnt\Cart\Sessions;


interface SessionRepositoryInterface
{

    public function add($item);

    public function update($items);

    public function remove($keys);

    public function get($key);

    public function all();

    public function clear();



}