2026-07-23

✔ Fixed Redis queue issue
Cause:
php-redis extension wasn't installed.

Solution:
sudo apt install php-redis
Restart PHP-FPM

Learned:
Always check storage/logs/laravel.log before guessing.

//for installing redis via ubuntu
sudo apt update
sudo apt install redis-server -y
sudo service redis-server start
redis-cli ping

storage/logs/laravel.log - for checking logs on laravel on windows os
tail -n 50 storage/logs/laravel.log - a linux command for checking logs on laravel on linux os

Problem
  2026_07_24_135450_create_media_models_table ......................................................................................... 31.52ms FAIL

   BadMethodCallException 

  Method Illuminate\Database\Schema\Blueprint::test does not exist.

  at vendor/laravel/framework/src/Illuminate/Macroable/Traits/Macroable.php:115
    111▕      */
    112▕     public function __call($method, $parameters)
    113▕     {
    114▕         if (! static::hasMacro($method)) {
  ➜ 115▕             throw new BadMethodCallException(sprintf(
    116▕                 'Method %s::%s does not exist.', static::class, $method
    117▕             ));
    118▕         }
    119▕ 

  1   database/migrations/2026_07_24_135450_create_media_models_table.php:22
      Illuminate\Database\Schema\Blueprint::__call()
      +4 vendor frames 

  6   database/migrations/2026_07_24_135450_create_media_models_table.php:14
      Illuminate\Support\Facades\Facade::__callStatic()

      Solution - change the data type and migrate again.

      composer require doctrine/dbal //->change()

      tail -f storage.logs/laravel.log