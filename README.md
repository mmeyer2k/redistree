# :deciduous_tree:RedisTree
RedisTree is a Laravel 5 package that provides an elegant web interface for Redis. It helps you monitor your server statistics and explore your Redis key/value pairs hierarchically.

<a href="SCREENSHOTS.md">Screenshots</a>

## Install
### Add to Composer
```
composer require "mmeyer2k/redistree=dev-master"
```
### Add Service Provider
Add the following line into the 'providers' array in `config/app.php`
```php
'Mmeyer2k\RedisTree\RedisTreeServiceProvider',
```
### Add Routes
To allow for full customization of the URL and security mechanisms employed, routing to the RedisTree controller is left to the developer. To serve the GUI from `/redistree` **without any kind of security**, add the following stub to your `routes.php` file
```php
\Route::controller('redistree', '\Mmeyer2k\RedisTree\RedisTreeController');
```

## Features
1. Ability to choose what characters represent keyspace separators.
2. Toggleable prompting before destructive actions.
3. Simple integration with existing Laravel 5 applications.
4. Elegant and intuitive interface.
