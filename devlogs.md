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