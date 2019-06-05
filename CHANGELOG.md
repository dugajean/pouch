# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 2.2

### Changed

- Can now store primitive types and arrays without the need of a Closure

### Added

- Ability to access array elements via dot notation
- Feature to hook into get/set of the container and ability to manipulate your data or setup anything via callback
  - Can hook into all get/set calls
  - Can hook into specific keys
- Ability to use `count` on any Pouch instance to see the count of stored items

## 2.1.1

### Added

- Ability to bind named items during mass-bind
- Compliance with PHAR files (Pouch works when bundled in a PHAR archive)

## 2.0 - Backwards compatible

### Added

- The concept of items to store information within the container
- New `alias` method to create a reference to an existing key
- Ability to autowire parameters only by name and no type-hint
  - Example: `pouch()->bind('foo', function () { return 'foo' }, true);` and then in your constructor: `public function __construct($foo) {}` would resolve to the string *foo*
- New `item` method to fetch the `Item` object
- Factory items can be called with different arguments every time they are needed. More about this in the wiki
  
### Changed 

- Complete rehaul of the container storage with the `Item` object
- Factories have been simplified and merged with the item concept
- Fetching the raw closure is now way simpler
- Extracted traits for the factory related methods and alias methods
- Requires PHP 7.1+ instead of PHP 7.0+

### Removed

- Old Factory class, now included within the item logic

## 1.2

### Changed

- The `get` method is now the main container fetching method instead of resolve.
- The `resolve` method in now used to go one step further if necessary and call `getContent()` if we're dealing with an internal container.

## 1.1.1 - 2019-03-11

## Added

- Ability to provide a fully qualified class path when registering a namespace and which will then be used to resolve the namespace off it.

## Fixed

- An invalid test case.

## 1.1 - 2019-03-11

### Changed

- Container elements will only be instantiated/loaded on request and not on every page load.

### Removed

- Extending previously set container elements (`extend` method) removed. Might be reintroduced later again.

## 1.0 - 2019-03-01

### Fixed

- Now allows for proper injecting of more nested dependencies without any interaction.
- Properly handle pouch dependencies (`\Pouch\Key`) resolving in nested cases.

## 0.5.0 - 2019-01-13

### Added

- Container content extension: if at some later point in the application you need to change upon the previous content of a key, you can now do so by using the `extend` method.
- Declaration of a bind as a factory: This allows the creation of different instances of an object on consecutive resolves of a key (default behavior always pulls the same instance).

### Changed

- Removed `__callStatic` which was meant to add the option to call all Pouch methods statically for syntactic sugar or personal preference. Now it's either the `pouch()` helper or a completely new Pouch instance.
- Cache handling moved to a `Cacheable` trait which `Pouch` now uses.
- Can now fully work with new instances of Pouch instead of relying on the `pouch()` helper.

### Fixed

- Resolvables now accept an argument which holds a container, allowing for a swap to a different instance other than the global `pouch()` one.

## 0.4.0 - 2019-01-04

### Added 

- Magic methods: `__get`, `__isset` and `__unset` to manage container keys
- ACPu caching for expensive operations
- Ability to switch the cache driver by providing a PSR-16 compatible caching package during bootstrap
- New helper to deal with the cache (`pouchCache`)

### Fixed

- Lots of method docblocks

## 0.3.0 - 2018-12-29

### Added

- PSR-11 compliance
- Can use the `pouch()` helper function in addition to calling methods statically.
- Ability to reference a dependency stored within the container which might not be an actual class with `\Pouch\~Key~`
- Wiki pages and API documentation

### Fixed 

- Exceptions narrowed down and switched to comply to PSR-11 as well.

## 0.2.0 - 2018-12-25

### Added
- Ability to override any class stored in the container when using `registerNamespaces()`
- Constructor arguments get the automatic injection benefits

### Fixed
- Inability to have more than one dependency injected through a parameter

## 0.1.0 - 2018-12-24

### Added
- Functionality to store data in an IoC container
- Ability to retrieve data from the container
- Register a namespace recursively for own application
- Ability to use typehints to fetch new instances of any data within your app, including dependencies

### Fixed
- Inability to have more than one dependency injected through a parameter
