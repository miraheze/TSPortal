# Release Notes

TSPortal follows a basic numerical increase system for releases and not Semantic Versioning.
The main reasoning behind this choice is the software is not built to be extended upon, therefore no stable public API exists.

## [Unreleased](https://github.com/miraheze/TSPortal/compare/v34...main)

### Added

- Added `use function` statements.
- Added InvestigationNew trigger when creating an investigation from a report.
- Added the ability to flag automatic reports when created via the API.
- Added UI feedback for automatically created reports to aid a bit with triaging them.
- Added DPA ID to the specific DPA page view.
- Added a copy to clipboard button for DPA usernames and IDs.
- Added the ability to generate PDFs from investigations.
- Added the ability to reopen reports that were closed without further action.
- Added the ability to open a DPA directly from a report.
- Added a user event when investigation is created from a report, rather than only when it is manually created separately.
- Added validation to require supplying evidence to reports.
- Added the `barryvdh/laravel-dompdf` library for generating PDFs from investigations.
- Added check that a user no longer exists in MediaWiki when approving a DPA.
- Added some missing messages.

### Fixed

- Fixed query used in DPA POST API.

### Changed

- Changed to use `@unless` in blade templates where it makes sense to.
- Changed `request()->session()->get()` to more simple `session()` in blade template.
- Changed to use `$request->boolean()` where possible.
- Changed UI for DPAs and Reports.
- Changed the `app.name` config to be just TSPortal and use it in the interface.
- Upgraded external libraries:
  - Upgraded laravel/framework from 13.1.1 to 13.2.0.
  - Upgraded laravel/socialite from 5.25.0 to 5.26.0.

## Version 34 (2026-03-23)

### Added

- Added proper `down()` implementation for users table migration.
- Added PHP type-hints wherever possible.
- Added proper implementation for appeals count on home page.
- Added some missing localization to blade templates.
- Added `strict_types` declarations to all PHP files.
- Added per-country (United States and United Kingdom) legislation cheat sheets in investigations.
- Added `#[Override]` where possible.
- Added pagination to users list (each page shows 500 users).

### Fixed

- SECURITY: Fixed DPAAlreadyLive creating arbitrary users ([GHSA-f346-8rp3-4h9h](https://github.com/miraheze/TSPortal/security/advisories/GHSA-f346-8rp3-4h9h)).
- Fixed Popper in Bootstrap JS properly.
- Fixed to not save flags and add an event when no changes are actually made.
- Fixed an issue with a migration order.
- Fixed link to reports made in user view.
- Fixed link to investigations in user view.
- Fixed to show all reports by default when providing reporter or user query parameters.
- Fixed to show all investigations by default when providing assigned or subject query parameters.
- Fixed allowing users to view and update their own reports.
- Fixed checking for assigned when editing investigations.
- Fixed setting active on current navigation item.
- Fixed serializing user object in `users_events` when closing DPAs (properly insert as the actor and only store using the ID).
- Fixed some PHP type errors by adding extra validation to rules.
- Fixed DPAAlreadyLive not working for DPA requests for accounts that are not their own account.

### Changed

- Changed migrations to use anonymous classes.
- Changed to use PHP 8 property promotion wherever possible.
- Changed to use strict comparisons wherever possible.
- Changed to use strict `in_array` wherever possible.
- Changed the `User::STANDING` constant to be private and type-hinted it.
- Changed to use new attributes from Laravel 13.
- Changed to use `withProviders()` instead of providers.php.
- Changed to use `withSchedule()` instead of the console.php route for registering IALScheduler.
- Changed `auth()->id()` to `$request->user()->id` in AppealController.
- Changed `request()->session()->flash()` to `$request->session()->flash()` in all controllers.
- Changed fully qualified class names to `@use` in the home page view blade template.
- Changed IALController to use `IAL::` instead of `DB::table()` to be consistent with other controllers.
- Changed queries to not use `all()` wherever possible to improve performance.
- Changed `->orderBy()` to use `->latest()` wherever possible.
- Changed deprecated Rule to use ValidationRule.
- Changed manual Gate check to use `@can` in dpa view blade template.
- Changed to use `@checked`, `@disabled`, and `@selected` helpers in blade templates.
- Changed AtRiskAlert to use new Mailable architecture.
- Changed `minimum-stability` in composer to stable.
- Changed to use more efficient database Eloquent methods (`->count()`, `->exists()`, `->doesntExist()`) wherever possible.
- Changed the main user list route from `/user` to `/users` and renamed the associated view.
- Upgraded external libraries:
  - Upgraded laravel/framework from 12.54.1 to 13.1.1.
  - Upgraded laravel/tinker from 2.11.1 to 3.0.0.
  - Upgraded taavi/laravel-socialite-mediawiki from 1.6.0 to 1.7.1.
 
### Removed

- Removed usage of `is_null` wherever possible.
- Removed the need to access the User model directly in the ial view blade template.
- Removed the `->id` when setting user using IALFactory in the IAL API route to be consistent with others.
- Removed the `->id` when setting assigned using AppealFactory in InvestigationController to be consistent with others.
- Removed explicit `ext-json` requirement from composer since it is always available in PHP 8.0+.

## Version 33 (2026-03-14)

### Fixed

- Fixed broken Bootstrap JS.

## Version 32 (2026-03-13)

### Changed

- Changed `Paginator::useBootstrap()` (which uses Bootstrap 4) to `Paginator::useBootstrapFive()`.
- Changed DPAs to sort by the filed timestamp.
- Changed the query used in DPAController to not use `::all()` to improve performance.
- Upgraded external libraries:
  - Upgraded laravel/framework from 12.53.0 to 12.54.1.
  - Upgraded laravel/socialite from 5.24.3 to 5.25.0.
 
### Removed

- Removed stub Controller class.

## Version 31 (2026-03-10)

### Fixed

- Fixed associations in IALScheduler to actually show properly rather than random numbers.

### Changed

- Changed IALScheduler to only count the number of actions per actor rather than listing the same actor repeatedly.
- Changed the query used in IALScheduler to not use `::all()` to improve performance.
- Upgraded external libraries:
  - Upgraded Bootstrap from 5.3.0-alpha1 to 5.3.8.

## Version 30 (2026-03-04)

### Added

- SECURITY: Added validation to the evidence field when creating a DPA for another user ([GHSA-gfhq-7499-f3f2](https://github.com/miraheze/TSPortal/security/advisories/GHSA-gfhq-7499-f3f2)).
- Added new InvestigationReopened event.

### Fixed

- Fixed a logic error causing InvestigationReopened events to be triggered when the Investigation is actually closed.
- Fixed webhook notifications to not say "New" before "Investigation" if it's not a new investigation (if state is closed or reopened, it removes the "New").

### Changed

- Changed the InvestigationClosed event to only be closed, not reopened as well.
- Upgraded external libraries:
  - Upgraded laravel/framework from 12.52.0 to 12.53.0.
  - Upgraded laravel/serializable-closure from 2.0.9 to 2.0.10.
  - Upgraded laravel/socialite from 5.24.2 to 5.24.3.

## Version 29 (2026-02-20)

### Fixed

- Restored webhook event listener functionality.

## Version 28 (2026-02-20)

### Fixed

- Fixed exception in DPAPolicy.

### Changed

- Upgraded external libraries:
  - Upgraded laravel/framework from 12.49.0 to 12.52.0.
  - Upgraded laravel/serializable-closure from 2.0.8 to 2.0.9.
  - Upgraded laravel/tinker from 2.11.0 to 2.11.1.
  - Upgraded nunomaduro/collision from ^8.6 to ^8.9.
  - Upgraded phpunit/phpunit from 12.5.7 to 13.0.5.

## Version 27 (2026-01-29)

### Changed

- Some more cleanup/migration to streamlined file structure added in Laravel 11.
- Upgraded external libraries:
  - Upgraded laravel/framework from 12.48.1 to 12.49.0.
  - Upgraded phpunit/phpunit from 12.5.7 to 12.5.8.
 
### Removed

- Removed laravel/sanctum. No longer included by default and the only usage in this was project was unnecessary.

## Version 26 (2026-01-25)

### Added

- Added development-only library nunomaduro/collision.
- Added initial support for PHPUnit tests.

### Changed

- Upgraded external libraries:
  - Upgraded laravel/framework from 12.46.0 to 12.48.1.
  - Upgraded laravel/sanctum from 4.2.2 to 4.2.4.
  - Upgraded laravel/serializable-closure from 2.0.7 to 2.0.8.
  - Upgraded laravel/socialite from 5.24.1 to 5.24.2.
  - Upgraded doctrine/dbal from ^4.2 to ^4.4.
  - Upgraded phpunit/phpunit from 12.5.4 to 12.5.7.
- Moved some external libraries from require to require-dev:
  - fakerphp/faker
  - mockery/mockery
  - phpunit/phpunit
- Migrate to the new streamlined file structure which was added in Laravel 11.
- Some configuration options renamed to match what is now built into the framework.

## Version 25 (2026-01-24)

### Fixed

- Fixed casting for datetime fields.

## Version 24 (2026-01-24)

### Fixed

- Added migration to change ial comments field to LONGTEXT rather than STRING to avoid SQL errors.
- Fixed query error when updating assigned field in appeals.
- Improved performance of loading own DPA requests and reports.

### Changed

- Removed support for PHP versions before 8.4.
- Renamed environmental variable FILESYSTEM_DRIVER to FILESYSTEM_DISK.
- Upgraded external libraries:
  - Upgraded fakerphp/faker from 1.21.0 to 1.24.1.
  - Upgraded guzzlehttp/guzzle from 7.5.0 to 7.10.0.
  - Upgraded laravel/framework from 8.83.28 to 12.46.0.
  - Upgraded laravel/sanctum from 2.15.1 to 4.2.2.
  - Upgraded laravel/serializable-closure from 1.2.2 to 2.0.7.
  - Upgraded laravel/socialite from 5.6.1 to 5.24.1.
  - Upgraded laravel/tinker from 2.8.0 to 2.11.0.
  - Upgraded doctrine/dbal from ^2.13 to ^4.2.
  - Upgraded phpunit/phpunit from 9.5.25 to 12.5.4.
  - Upgraded mockery/mockery from 1.5.1 to 1.6.12.
 
### Removed

- Removed fruitcake/laravel-cors. Laravel 9+ includes CORS middleware in core.

# Version 23 (2025-08-03)

### Fixed

- Added migration to change dpas underage field to LONGTEXT rather than TEXT to avoid SQL errors.

## Version 22 (2025-08-03)

### Fixed

- Fixed version of DBAL that we use.

## Version 21 (2025-08-03)

### Added

- Added doctrine/dbal package to composer (required for migrations).

### Changed

- Removed support for PHP versions before 8.2.

### Fixed

- Fixed migration to change dpas underage field to TEXT rather than VARCHAR.

## Version 20 (2024-09-07)

### Added

- Implement support for mattermost webhook notifications.

## Version 19 (2024-08-26)

### Fixed

- Added migration to change dpas underage field to TEXT rather than VARCHAR to avoid SQL errors.

## Version 18 (2024-02-24)

### Fixed

- Fixed "Integrity constraint violation: 1048 Column 'comments' cannot be null" with IAL
- Fixed "Argument 1 ($newFlags) must be of type array, null given" with updating flags

## Version 17 (2024-02-23)

### Fixed

- Fixed IAL fatal errors with accessing undefined index

## Version 16 (2024-02-09)

### Changed

- Update company footer with EIN
- Replace mentions of Miraheze Limited with WikiTide Foundation and UK law with US
- Bump version

## Version 15 (2024-01-25)

### Changed

- Bumped version to 15.

## Version 14 (2024-01-25)

### Changed

- Updated company owner in footer-company.

## Version 13 (2024-01-25)

### Changed

- Updated composer lock file.

## Version 12 (2024-01-25)

### Changed

- Changed default SMTP settings
- Added support for php 8

## Version 11 (2023-02-06)

### Added

- Added phpunit and mockery

### Changed

- Upgrade external dependencies
	- Bootstrap -> 5.3.0-alpha
	- fakerphp/faker -> 1.21.0
	- laravel/framework -> 8.83.27
	- laravel/socialite -> 5.6.1
	- laravel/tinker -> 2.8.0
- Modified IAL Digest to only run when actions have occurred.

### Fixed

- Corrected language codes for toast messages.
- Fixed IAL showing as active in sidebar always.
- Fixed IAL fatal errors with accessing undefined index.

## Version 10 (2022-11-13)

### Added

- API for creating DPA and Reports.
- Internal Actions Log to ensure all actions can be transparent.

### Changed

- Changed flash messages to show colour for visual confirmation.

## Version 9 (2022-10-22)

### Fixed

- Fix table name in down() for appeals migration.
- Appeals i18n and policy fixes.
- Return a single DPA model for Dispatching.

## Version 8 (2022-10-03)

### Added

- Validate DPAs for open requests to prevent duplicates.
- Implement appeals mechanism for investigations.
- Visual feedback for actions that change states (toasts).

### Changed

- Upgraded guzzlehttp/guzzle to 7.5.0.
- Upgraded laravel/framework to 8.83.24.
- Upgraded laravel/serializable-closure to 1.2.2.
- Upgraded laravel/socialite to 5.5.5.

### Fixed

- Correctly display DPA ID on new event being triggered.

## Version 7 (2022-08-05)

### Fixed

- API: Use correct name for DPA subject.

## Version 6 (2022-07-31)

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
