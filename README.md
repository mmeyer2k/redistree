# RedisTree
RedisTree is a Laravel 5 package that provides an elegant web interface for the manipulation and heirarchical visualization of your data stored in Redis.

<a href="https://cdn.rawgit.com/mmeyer2k/mmeyer2k.github.io/master/storage/redistree/screen1.png" target="_blank">Screenshot</a>

## Install
### Add to Composer
```
composer require mmeyer2k/redistree
```
### Add Service Provider
Add the following line into 'providers' array in `config/app.php`
```php
'Mmeyer2k\RedisTree\RedisTreeServiceProvider',
```
### Add Routes
To allow for full customization of the URL and security mechanisms employed, routing to the RedisTree controller is left to the developer. To serve the GUI from `/redistree` **without any kind of security**, add the following stub to your `routes.php` file
```php
\Route::controller('redistree', '\Mmeyer2k\RedisTree\RedisTreeController');
```
