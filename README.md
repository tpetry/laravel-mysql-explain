# Laravel MySQL Visual Explains

![License][icon-license]
![PHP][icon-php]
![Laravel][icon-laravel]
[![Latest Version on Packagist][icon-version]][href-version]
[![GitHub Unit Tests Action Status][icon-tests]][href-tests]
[![GitHub Static Analysis Action Status][icon-staticanalysis]][href-staticanalysis]
[![GitHub Code Style Action Status][icon-codestyle]][href-codestyle]

MySQL Query optimization with the `EXPLAIN` command is unnecessarily complicated: The output contains a lot of cryptic information that is incomprehensible or entirely misleading.

This Laravel package collects many query metrics that will be sent to [mysqlexplain.com](https://mysqlexplain.com) and transformed to be much easier to understand.

## Installation

You can install the package via composer:

```bash
composer require tpetry/laravel-mysql-explain
```

## Usage

### Query Builder

Three new methods have been added to the query builder for very easy submission of query plans: 

| Type                                      | Action                                                              |
|-------------------------------------------|---------------------------------------------------------------------|
| `visualExplain`                           | returns URL to processed EXPLAIN output                             |
| `dumpVisualExplain`                       | dumps URL to processed EXPLAIN output and continue normal execution |
| `ddVisualExplain`                         | dumps URL to processed EXPLAIN output and stops execution           |
| `logVisualExplain`                        | logs URL to processed EXPLAIN output and continue normal execution  |
| `explainForHumans` ***(deprecated)***     | returns URL to processed EXPLAIN output                             |
| `dumpExplainForHumans` ***(deprecated)*** | dumps URL to processed EXPLAIN output and continue normal execution |
| `ddExplainForHumans` ***(deprecated)***   | dumps URL to processed EXPLAIN output and stops execution           |

```php
// $url will be e.g. https://mysqlexplain.com/explain/01j2gcrbsjet9r8rav114vgfsy
$url = Film::where('description', 'like', '%astronaut%')
    ->visualExplain();

// URL to EXPLAIN will be printed to screen
$users = Film::where('description', 'like', '%astronaut%')
    ->dumpVisualExplain()
    ->get();

// URL to EXPLAIN will be printed to screen & execution is stopped
Film::where('description', 'like', '%astronaut%')
    ->ddVisualExplain();
```

### Raw Queries

In some cases you are executing raw SQL queries and don't use the query builder. You can use the `MysqlExplain` facade to get the EXPLAIN url for them:

```php
use Tpetry\MysqlExplain\Facades\MysqlExplain;

// $url will be e.g. https://mysqlexplain.com/explain/01j2gctgtheyva7a7mhpv8azje
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
[icon-laravel]: https://img.shields.io/badge/Laravel-6.*--11.*-blue
[icon-license]: https://img.shields.io/github/license/tpetry/laravel-mysql-explain?color=blue&label=License
[icon-codestyle]: https://img.shields.io/github/actions/workflow/status/tpetry/laravel-mysql-explain/code-style.yml?label=Code%20Style
[icon-php]: https://img.shields.io/packagist/php-v/tpetry/laravel-mysql-explain?color=blue&label=PHP
[icon-staticanalysis]: https://img.shields.io/github/actions/workflow/status/tpetry/laravel-mysql-explain/static-analysis.yml?label=Static%20Analysis
[icon-tests]: https://img.shields.io/github/actions/workflow/status/tpetry/laravel-mysql-explain/unit-tests.yml?label=Tests
[icon-version]: https://img.shields.io/packagist/v/tpetry/laravel-mysql-explain.svg?label=Packagist



