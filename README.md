# RedisTree
RedisTree is a Laravel package that provides an elegant web interface for Redis.
It assists you in monitoring your server statistics and exploring your Redis key hierarchy.

<a href="SCREENSHOTS.md">Screenshots</a>

**Requires PHP 7.1+**

## Install
### Add to Composer
```
composer require "mmeyer2k/redistree:^1.0"
```
### Add Service Provider
Add the following line into the 'providers' array in `config/app.php`
```php
Mmeyer2k\RedisTree\RedisTreeServiceProvider::class,
```
### Add Routes
To allow for full customization of the URL and security mechanisms employed, routing to the RedisTree controller is left to the developer.
To serve the GUI from `/redistree` **without any kind of security**, add the following stub to your `routes.php` file.
```php
\Mmeyer2k\RedisTree\RedisTreeModel::registerRoutes();
```

### Security
Place the `registerRoutes()` within a route group to provide authentication flexibility.

## Features
1. Ability to choose what characters represent keyspace separators.
2. Toggleable prompting before destructive actions.
3. Simple integration with existing Laravel 5 applications.
4. Elegant and intuitive interface.
