# Release Notes

TSPortal follows a basic numerical increase system for releases and not Semantic Versioning.
The main reasoning behind this choice is the software is not built to be extended upon, therefore no stable public API exists.

## [Unreleased](https://github.com/miraheze/TSPortal/compare/v5...master)

### Changed

- Upgraded fakerphp/faker to 1.20.0.
- Upgraded laravel/framework to 1.83.23.
- Upgraded laravel/socialite to 5.5.3.

### Fixed

- Fix models passed through to events.
- User assigned needs to be passed ID not object.

## Version 5 (2022-07-03)

### Fixed

- Add support for HTTP web proxies.

## Version 4 (2022-07-03)

### Added

- Allow disabling user login through a flag.
- Implement sending emails for At Risk Reports.
- Implement Discord notifications for all new resources and major reports.

### Changed

- Add 'no comments' default text for stylistic purposes.

### Fixed

- Correctly assign subject field based on modified Report structure.
- Update usages of subject to user in DPA.

## Version 3 (2022-07-02)

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
