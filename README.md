# Laravel Blade Extension Classes

This Laravel >=5.5 package allows you to organize your Blade extensions into classes. It provides a simple container pass that registers all your extensions with the Blade compiler using a simple tag.

Organizing Blade extensions as classes in the service container allows you to group extension functionality within one object, allowing you to inject dependencies through the service container and provide shared protected/private methods.

## Installation

You can install this package via Composer using the following command:

```
composer require bitpress/blade-extensions
```

This package will automatically register the included service provider.

## Usage

At a high level, the goal of this package is to make it easy and convenient to register blade extensions as classes from the service container automatically using service container tagging. Here's the gist of how it works:

1. Create a new Extension class with `php artisan make:blade Example`
2. Register the Extension in a service provider's `register()` method
3. Tag the service with `blade.extension`
4. The `BladeExtensionServiceProvider` automatically wires up the directives in the blade compiler during `boot()`.

### Creating a new Extension Class

It would be annoying to create a new extension class from scratch each time, so this package provides an artisan command to create your extensions under the `App\Blade` namespace:

```
# Creates App\Blade\CartExtension
php artisan make:blade Cart
```

### Defining the Extension Directives and Conditionals

Once you create a blade extension class, you can define supported directives, conditionals, or both. Custom Blade directives and conditionals are simply [PHP callables](http://php.net/manual/en/language.types.callable.php):

```php
<?php

namespace App\Blade;

use BitPress\BladeExtension\Contracts\BladeExtension;

class CartExtension implements BladeExtension
{
    public function getDirectives()
    {
        return [
            'cartcount' => [$this, 'getCartCount']
        ];
    }

    public function getConditionals()
    {
        return [
            'cartempty' => [$this, 'isCartEmpty']
        ];
    }

    public function getCartCount()
    {
        // logic to return cart count
    }

    public function isCartEmpty()
    {
        // logic for empty cart
    }
}
```

Note that Blade extension classes implement the `BladeExtension` contract, which includes `getDirectives()` and `getConditionals()`. Even if you don't plan on registering any conditionals, for example, you must implement the `getConditionals()` method and return an empty `array`.

Your custom blade extension will be registered in the service container, so you can define a `__construct()` method and inject services from the container. The fact that Blade extensions are services allows you to group common code around your blade extensions, including those directives that depend on outside services that are registered in the container.


### Registering a New Blade Service

After you define a blade extension, you still need to register it in the service container and tag it properly so the `BladeExtensionServiceProvier` can define the PHP callables in the Blade compiler during `boot()`. Because registering blade extensions and tagging them manually can be somewhat tedious, this package provides a `BitPress\BladeExtension\Container\BladeRegistrar` class to define the extension in the container and tagging it properly.

To register your extension, you need to define it in a Service provider's `register()` method. For example, `App\Providers\AppServiceProvider`:

```php
use Illuminate\Support\ServiceProvider;
use BitPress\BladeExtension\Container\BladeRegistrar;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        BladeRegistrar::register(\App\Blade\CartExtension::class);
    }
}
```

If you need to, you can create the service in a `Closure`:

```php
use \App\Blade\CartExtension::class;

BladeRegistrar::register(CartExtension::class, function () {
    // Do stuff...

    return new CartExtension($stuff);
});
```

You can also use the `blade_extension()` helper function to register the service if you prefer:

```php
public function register()
{
    blade_extension(\App\Blade\CartExtension::class);
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
