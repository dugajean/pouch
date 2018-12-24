# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 0.1.0 - 2018-12-24

### Added
- Functionality to store data in an IoC container
- Ability to retrieve data from the container
- Register a namespace recursively for own application
- Ability to use typehints to fetch new instances of any data within your app, including dependencies

### Fixed
- Inability to have more than one dependency injected through a parameter
