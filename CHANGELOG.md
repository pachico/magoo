# Changelog

## 2.2.0
- MagooLogger implements Psr\Log\LoggerInterface now. The magic function __call was replaced with the real functions of the interface. (thanks to @GordonGuenther)
- Tests have been rewritten following AAA principals
- Added tests for 7.1 and 7.2

## 2.1.0

### Added
- MagooLogger learned to use MagooArray to mask logger context.

### Fixed
- README.md was updated to reflect 2.* API.

## 2.0.0

### Added
- Package is now PSR2 compliant.
- IMPORTANT: it breaks BC.
