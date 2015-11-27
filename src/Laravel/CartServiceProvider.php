<?php

namespace Mnt\Cart\Laravel;

use Illuminate\Support\ServiceProvider;
use Mnt\Cart\Cart;
use Mnt\Cart\Sessions\IlluminateSession;
use Mnt\Cart\Sessions\SessionRepository;

class CartServiceProvider extends ServiceProvider
{
	public function register(){
		$this->prepareResources();
		$this->registerSession();
		$this->registerCollectionSession();
		$this->registerCart();
	}

	protected function registerSession()
	{
		$this->app->singleton('cart.session', function($app){
			return new IlluminateSession($app['session.store']);
		});
	}

	protected function registerCollectionSession()
	{
		$this->app->singleton('cart.collection.session', function($app){
			return new SessionRepository($app['cart.session']);
		});
	}

	protected function registerCart()
	{
		$this->app->singleton('cart', function($app){
			$config = $app['config']->get('mnt.cart');

			$cartItems  = array_get($config, 'cartItems.model');

			return new Cart($app['cart.collection.session'], $app['events'], $cartItems);
		});
	}

	protected function prepareResources()
	{
		// Publish config
		$config = realpath(__DIR__.'/../config/config.php');
		$this->mergeConfigFrom($config, 'mnt.cart');
		$this->publishes([
			$config => config_path('mnt.cart.php'),
		], 'config');

		// Publish migrations
		$migrations = realpath(__DIR__.'/../migrations');
		$this->publishes([
			$migrations => $this->app->databasePath().'/migrations',
		], 'migrations');
	}
}