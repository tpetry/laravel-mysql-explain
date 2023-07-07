# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.2] - 2023-07-07
### Fixed
* Bindings had been handled incorrectly leading to errors when DateTimeInterface was used

## [1.0.1] - 2023-06-18
### Fixed
* Called method that is only public since Laravel 10

## [1.0.0] - 2023-06-12
### Added
* `explainForHumans()` for query builders
* `dumpExplainForHumans()` for query builders
* `ddExplainForHumans()` for query builders
* `MysqlExplain::submitBuilder($builder)`
* `MysqlExplain::submitQuery($connection, $sql, $bindings)`
