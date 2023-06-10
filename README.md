# Laravel MySQL Explains For Humans

![License][icon-license]
![PHP][icon-php]
![Laravel][icon-laravel]
[![Latest Version on Packagist][icon-version]][href-version]
[![GitHub Unit Tests Action Status][icon-tests]][href-tests]
[![GitHub Static Analysis Action Status][icon-staticanalysis]][href-staticanalysis]
[![GitHub Code Style Action Status][icon-codestyle]][href-codestyle]

MySQL Query optimization with the `EXPLAIN` command is unnecessarily complicated: The output contains a lot of cryptic information that is incomprehensible or entirely misleading.

This Larvel package collects many query metrics that will be sent to [explainmysql.com](explainmysql.com) and transformed to be much easier to understand.

## Installation

You can install the package via composer:

```bash
composer require tpetry/laravel-mysql-explain
```

## Usage

### Query Builder

Three new methods have been added to the query builder for very easy submission of query plans: 

| Type                   | Action                                                              |
|------------------------|---------------------------------------------------------------------|
| `explainForHumans`     | returns URL to processed EXPLAIN output                             |
| `dumpExplainForHumans` | dumps URL to processed EXPLAIN output and continue normal execution |
| `ddExplainForHumans`   | dumps URL to processed EXPLAIN output and stops execution           |


```php
// $url will be e.g. https://explainmysql.com/explain/613cf8a0-d76c-4386-9680-5b6b537c3463
$url = Film::where('description', 'like', '%astronaut%')
    ->explainForHumans();

// URL to EXPLAIN will be printed to screen
$users = Film::where('description', 'like', '%astronaut%')
    ->dumpExplainForHumans()
    ->get();

// URL to EXPLAIN will be printed to screen & execution is stopped
$users = Film::where('description', 'like', '%astronaut%')
    ->ddExplainForHumans()
    ->get();
```

### Raw Queries

In some cases you are executing raw SQL queries and don't use the query builder. You can use the `MysqlExplain` facade to get the EXPLAIN url for them:

```php
use Tpetry\MysqlExplain\Facades\MysqlExplain;

// $url will be e.g. https://explainmysql.com/explain/89eef861-e01b-4ab1-8fa4-4f7dd9d33ead
$url = MysqlExplain::submitQuery(
    DB::connection('mysql'),
    'SELECT * FROM actor WHERE first_name = ?',
    ['PENEL\'OPE'],
);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Tobias Petry](https://github.com/tpetry)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[href-codestyle]: https://github.com/tpetry/laravel-mysql-explain/actions/workflows/code-style.yml
[href-staticanalysis]: https://github.com/tpetry/laravel-mysql-explain/actions/workflows/static-analysis.yml
[href-tests]: https://github.com/tpetry/laravel-mysql-explain/actions/workflows/unit-tests.yml
[href-version]: https://packagist.org/packages/tpetry/laravel-mysql-explain
[icon-laravel]: https://img.shields.io/badge/Laravel-6.*--10.*-blue
[icon-license]: https://img.shields.io/github/license/tpetry/laravel-mysql-explain?color=blue&label=License
[icon-codestyle]: https://img.shields.io/github/actions/workflow/status/tpetry/laravel-mysql-explain/code-style.yml?label=Code%20Style
[icon-php]: https://img.shields.io/packagist/php-v/tpetry/laravel-mysql-explain?color=blue&label=PHP
[icon-staticanalysis]: https://img.shields.io/github/actions/workflow/status/tpetry/laravel-mysql-explain/static-analysis.yml?label=Static%20Analysis
[icon-tests]: https://img.shields.io/github/actions/workflow/status/tpetry/laravel-mysql-explain/unit-tests.yml?label=Tests
[icon-version]: https://img.shields.io/packagist/v/tpetry/laravel-mysql-explain.svg?label=Packagist



