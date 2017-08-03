# Daisycon

Laravel package to import data via Daisycon Rest API (affiliates)

## Installation

First install the package via [Composer](http://getcomposer.org):

    composer require "bahjaat/daisycon":"^2.0"

Or manually include it into your composer.json file
```json
{
    "require": {
        "bahjaat/daisycon": "^2.0"
    }
}
```
and run `composer update` after that.

## Setup

<em>Skip this step when using Laravel 5.5 or above.</em>

Edit your `config/app.php` file, to include the service provider:

`Bahjaat\Daisycon\DaisyconServiceProvider::class`

### Publishing config files
```bash
php artisan vendor:publish --provider="Bahjaat\Daisycon\DaisyconServiceProvider" --tag="config"
php artisan vendor:publish --provider="Cviebrock\EloquentSluggable\ServiceProvider"
```

### Migrate the database
`php artisan migrate`

## Configuration
After setting up you have to configure your Daisycon settings at `app/config/daisycon.php`
Really important attribute are:
* username
* password
* media_id
* publisher_id

When using >= v2.0.6 you can also set your ```.env``` file with the following variables:

```
DAISYCON_USERNAME
DAISYCON_PASSWORD
DAISYCON_MEDIA_ID
DAISYCON_PUBLISHER_ID
```

## Seeding database
For your convenience there are some database seed classes provided with the package.
- The first one is adding some country(codes) into the databse.
- The second one is adding some active programs.

Just run these commands:

* `php artisan db:seed --class=CountrycodesTableSeeder`
* `php artisan db:seed --class=ActiveProgramTableSeeder`

# Artisan
After all, you can import your programs, feeds and subscriptions into you own database. Go hit the road!

```bash
php artisan daisycon:get-programs
php artisan daisycon:get-subscriptions
php artisan daisycon:get-feeds
php artisan daisycon:get-products
```

# Todo

- Writing tests

# Last words
Need some adjustments? Please create a pull-request and we will make this package a better one together.

# LICENSE

This library is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation; either version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU Lesser General Public License for more details or LICENSE.txt distributed with this class.
