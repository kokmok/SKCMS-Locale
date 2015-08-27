SKCMS-Locale
==========

This package is currently under development, more documentation and features will come soon.


# The Locale bundle of SKCMS for Symfony2

## Installation

### Download dependecies via composer
use php composer.phar instead of composer on Windows
```
composer require SKCMS/locale-bundle:dev-master
```
### Install Languages, Currencies and countries.
you have to install countries last
```
php app/console skcms:import:languages
php app/console skcms:import:currencies
php app/console skcms:import:countries
```


## Usage

This bundle will halp you with translations and other locale things like countries, addresses, currencies..

### Translate entities
You just have to enable translatable in config.yml 
```
stof_doctrine_extensions:
    orm:
        default:
            translatable: true
```
have a parameter locale in parametrs.yml or in config.yml

and in config.yml:
```
framework:
    default_locale:  "%locale%"

```

Now all you have to do is modify a bit tyour tranlatable entities adn repositories (look how it's done in SKCMS\LocaleBundle\Country ). 

