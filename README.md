# Daisycon

Laravel package to import data via Daisycon Rest API (affiliates)

## Installatie

1.	composer.json aanpasen `"bahjaat/daisycon": "dev-master"`
2.	`composer update`
3.	`'Bahjaat\Daisycon\DaisyconServiceProvider'` toevoegen aan app/config/app.php
4.	Als er nog geen migrations tabel is: `php artisan migrate:install`
5.	Daarna `php artisan config:publish bahjaat/daisycon`
6.	config.php aanpassen: app/config/packages/bahjaat/daisycon/config.php (username / password / media_id)
7.	`php artisan migrate --path="vendor/bahjaat/daisycon/src/database/migrations/"` (werkt (nog) niet met `--package bahjaat/daisycon`; help wanted!?)
8.	`php artisan db:seed --class="CountrycodesTableSeeder"`
9.	`php artisan db:seed --class="ActiveProgramTableSeeder"`
10.	`php artisan daisycon:getprograms`
11.	`php artisan daisycon:getfeeds`
12. Vul de tabel 'active_programs' met program_id's welke je geïmporteerd wil hebben. Eventueel kun je ook een custom_categorie meegeven zodat deze waarde ook meegenomen wordt in je data tabel.
12.	Nu alle xml's doorlopen in en data tabel importeren via `php artisan daisycon:import-data`
