# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.3.2] - 2024-09-13
### Changed
* Improved the detection for MariaDB databases

## [1.3.1] - 2024-07-13
### Changed
* Removed obsolete logic  for old explain api so the spatie/invade dependency can be removed (#7)
* Uses Laravel builder to sql implementation when available

## [1.3.0] - 2024-07-11
### Added
* Improved error messages for unsupported databases/queries
* New visual explain methods

### Changed
* Uses api.mysqlexplain.com v2 API

## [1.2.0] - 2024-03-13
### Changed
* Use new mysqlexplain.com project domain

## [1.1.0] - 2024-03-12
### Added
* Laravel 11 compatibility

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
