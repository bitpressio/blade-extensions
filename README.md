# Laravel Blade Extension Classes

This Laravel >=5.5 package allows you to organize your Blade extensions into classes. It provides a simple container pass that registers all your extensions with the Blade compiler using a simple tag.

Organizing Blade extensions as classes in the service container allows you to group extension functionality within one object, allowing you to inject dependencies through the service container and provide shared protected/private methods.

## Installation

You can install this package via Composer using the following command:

```
composer require bitpress/blade-extensions
```

This package will automatically register the included service provider.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
