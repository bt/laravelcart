<?php

/**
 * Created by IntelliJ IDEA.
 * User: alanyu
 * Date: 25/11/2015
 * Time: 12:21 PM
 */

namespace Mnt\Cart\Sessions;

interface SessionInterface
{
    /**
     * Put a value in the session.
     *
     * @param  mixed  $value
     * @return void
     */
    public function put($value);
    /**
     * Returns the session value.
     *
     * @return mixed
     */
    public function get();
    /**
     * Removes the session.
     *
     * @return void
     */
    public function forget();
}