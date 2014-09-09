Daisycon
========

Laravel package to import data via Daisycon API (affiliates)

1.	composer.json > "bahjaat/daisycon": "dev-master"
2.	composer update
3.	'Bahjaat\Daisycon\DaisyconServiceProvider' > app/config/app.php
4.	als er nog geen migrations tabel is: php artisan migrate:install
5.	php artisan config:publish bahjaat/daisycon
6.	edit file app/config/packages/bahjaat/daisycon/config.php (username / password / media_id)
7.	