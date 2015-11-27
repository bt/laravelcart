<?php
/**
 * Created by IntelliJ IDEA.
 * User: alanyu
 * Date: 25/11/2015
 * Time: 2:37 PM
 */

namespace Mnt\Cart\Sessions;

use Cartalyst\Support\Traits\RepositoryTrait;
use Mnt\Cart\Sessions\SessionRepositoryInterface;
use Mnt\Cart\Sessions\SessionInterface;


class SessionRepository implements SessionRepositoryInterface
{
    use RepositoryTrait;


    protected $session;

    protected $model = 'Mnt\Cart\Items\CollectionCartItem';

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function add($item)
    {
        $items = $this->session->get();

        //strips id key from the array
        $data = $item;
        unset($data['id']);

        //assigns id to array
        $items[$item['id']] = $data;

        $this->session->put($items);

        return $this->values($items);
    }

    public function update($updates)
    {
        $items = $this->session->get();

        $keys = array_column($updates, 'id');

        foreach($updates as $update){
            if (array_key_exists($update['id'], $items)) {
                $data = $update;
                unset($data['id']);
                $items[$update['id']] = array_merge($items[$update['id']], $data);
            }
        }

        $this->session->put($items);

        return $this->values($items);

    }

    public function remove($keys)
    {
        $items = $this->session->get();

        foreach($keys as $key){
            unset($items[$key]);
        }

        $this->session->put($items);

        return $this->values($items);
    }

    public function all()
    {
        $items = $this->session->get();

        if($items === null){
            return [];
        }

        return $this->values($items);
    }

    public function clear()
    {
        $this->session->put([]);
    }

    public function get($key)
    {
        $items = $this->session->get();

        if(isset($items)){
            if (array_key_exists($key, $items)) {
                return $this->values([$key => $items[$key]])[0];
            }
        }
        return [];
    }

    protected function values($items)
    {

        $values = [];

        foreach($items as $key => $item){
            $item['id'] = $key;
            array_push($values , $item);
        }

        return $values;

    }



}