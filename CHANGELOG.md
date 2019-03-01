# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
