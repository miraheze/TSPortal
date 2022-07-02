# Release Notes

TSPortal follows a basic numerical increase system for releases and not Semantic Versioning.
The main reasoning behind this choice is the software is not built to be extended upon, therefore no stable public API exists.

## [Unreleased](https://github.com/miraheze/TSPortal/compare/v2...master)

### Fixed

- Prevent empty comments being submitted to user events history.
- Correctly render HTML tags on investigation pages.
- Rename subject to user for Reports.
- Rename subject to user for DPA.

## Version 2 (2022-06-27)

### Added

- Add new filtering options for investigations and reports.
- Filtering UI on investigations and reports list pages.

### Changed

- Modified how buttons and the nav bar displays on smaller screens to make them more accessible.
- Upgraded guzzlehttp/guzzle to v7.4.5.
- Upgraded laravel/framework to v8.83.17.

### Fixed

- Renamed migration files to 00 for users and 01 for dependants. Thank you to [paladox](https://github.com/paladox) for the fix for this!
- Attribute user flag changes to request user, not model user.
- Correctly mark investigations.assigned as a foreign key.

## Version 1 (2022-05-30)

Initial commit of all base code!
