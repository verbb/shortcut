# Changelog

## 5.0.2 - 2025-11-29

### Added
- Add control panel settings page.

## 5.0.1 - 2025-07-18

### Changed
- Update English translations.

### Fixed
- Fix an error when creating a shortcut item when no code is provided.

## 5.0.0 - 2024-05-12

### Changed
- Now requires PHP `8.2.0+`.
- Now requires Craft `5.0.0+`.
- Increase the size of link storage past 400 characters.

### Fixed
- Fix an error when uninstalling the plugin.
- Fix migration for `elementType`.

## 4.0.4 - 2025-07-18

### Changed
- Update English translations.

## 4.0.3 - 2024-04-10

### Fixed
- Fix an error when uninstalling the plugin.

## 4.0.2 - 2024-03-26

### Fixed
- Fix migration for `elementType`.

## 4.0.1 - 2024-03-22

### Changed
- Increase the size of link storage past 400 characters.

## 4.0.0 - 2022-07-19

### Changed
- Now requires PHP `8.0.2+`.
- Now requires Craft `4.0.0+`.

## 3.0.1 - 2022-07-19

### Added
- Add `hashLength` plugin setting to control the length of generated hashes in URLs.

### Fixed
- Fix an error when trying to generate a shortcut from a deleted element.
- Fix an error when generating shortcuts.

## 3.0.0 - 2022-07-18

> {note} The plugin’s package name has changed to `verbb/shortcut`. Shortcut will need be updated to 3.0 from a terminal, by running `composer require verbb/shortcut && composer remove superbig/craft3-shortcut`.

### Changed
- Migration to `verbb/shortcut`.
- Now requires Craft 3.7+.

## 2.0.3 - 2019-09-03

### Fixed
- Removed stray `var_dump`

## 2.0.2 - 2018-10-18

### Changed
- Added proper migration for upgrading from Craft 2 to Craft 3
- Removed Hashids dependency

## 2.0.1 - 2018-06-01

### Fixed
- Fixed create method for urls

## 2.0.0 - 2017-11-14

### Added
- Initial release
