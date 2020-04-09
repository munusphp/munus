# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.2.1] - 2020-04-09
### Added
- Traversable: drop - opposite to take 

## [0.2.0] - 2020-03-21
### Added
- Stream: append, appendAll, prepend, prependAll
- GenericList: appendAll, prependAll
- CompositeIterator

### BC breaks
- Sequence abstract for Stream and GenericList
- methods ofAll now accepts iterable (prev array)

## [0.1.4] - 2020-03-03
### Added
- Add isPresent method to Option

## [0.1.3] - 2020-02-25
### Removed
- Supplier interface - non ascii char in namespace

## [0.1.2] - 2020-01-09
### Fixed
- remove to specific annotations for compare method

## [0.1.1] - 2020-01-08
### Fixed
- template annotations to prevent "Unable to resolve the template" error

## [0.1.0] - 2020-01-06
### Added
- Value: Lazy, Either, Option, TryTo
- Traversable: Stream, Set, Map, GenericList
- Tuple
- Stream collectors
- Iterators
- Value comparator
