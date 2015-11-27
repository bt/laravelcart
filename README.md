# Laravel Cart
A shopping cart made for Laravel 5.

## Installation

### Laravel 5

Add the following to your `composer.json` file:

```php
"require": {
	"mnt/cart": "dev-master"
}
```

then perform a `composer update`.

Now all you have to do is add the service provider of the package and alias the package. To do this open your `app/config/app.php` file.

Add a new line to the `service providers` array:

	'Mnt\Cart\Laravel\CartServiceProvider::class'

And finally add a new line to the `aliases` array:

	'Cart' => 'Mnt\Cart\Laravel\Facades\Cart::class'
	
Run the following command to publish the migrations and config file.

```php artisan vendor:publish --provider="Mnt\Cart\Laravel\CartServiceProvider"```

