Daisycon
========
Laravel package to import data via Daisycon Rest API (affiliates)
### Laravel 5
```json
{
    "require": {
        "bahjaat/daisycon": "dev-master"
    }
}
```
### Installatie
* `composer require bahjaat/daisycon:dev-master`
* `'Bahjaat\Daisycon\DaisyconServiceProvider::class'` toevoegen aan **config/app.php**
* Als er nog geen migrations tabel is: `php artisan migrate:install`
* `php artisan vendor:publish`
* config.php aanpassen: app/config/packages/bahjaat/daisycon/config.php (username / password / media_id)
* `php artisan migrate --path="vendor/bahjaat/daisycon/src/database/migrations/"` (werkt (nog) niet met `--package bahjaat/daisycon`; help wanted!?)  TODO
* `php artisan db:seed --class=CountrycodesTableSeeder`
* `php artisan db:seed --class=ActiveProgramTableSeeder`
* `php artisan daisycon:get-programs`
* `php artisan daisycon:get-feeds`
* Vul de tabel 'active_programs' met program_id's welke je geïmporteerd wil hebben. Eventueel kun je ook een custom_categorie meegeven zodat deze waarde ook meegenomen wordt in je data tabel.
* Nu alle xml's doorlopen in en data tabel importeren via `php artisan daisycon:import-data`
* TODO `php artisan daisycon:get-subscriptions`
* TODO `php artisan daisycon:fix-data`

Heb je op- of aanmerkingen, ik houd met graag aanbevolen voor vooral positief en opbouwende kritiek.
