# SemanticVersion

A modular, extensible library for PHP >= 5.6 providing APIs to support parsing & manipulation of semantic version numbers, comparators, ranges & collections.

The [Semantic Versioning Specification](http://semver.org/) describes the semantics and precedence rules used when parsing & manipulating version numbers etc.

[![Build Status](https://travis-ci.org/ptlis/semantic-version.png?branch=master)](https://travis-ci.org/ptlis/semantic-version) [![Code Coverage](https://scrutinizer-ci.com/g/ptlis/semantic-version/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ptlis/semantic-version/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ptlis/semantic-version/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ptlis/semantic-version/?branch=master) [![Latest Stable Version](https://poser.pugx.org/ptlis/semantic-version/v/stable.png)](https://packagist.org/packages/ptlis/semantic-version)

## Usage

### Parse Version

First create an instance of the VersionEngine:

```php
    use ptlis\SemanticVersion\VersionEngine();

    $engine = new VersionEngine();
```

Then call the `parseVersion` method passing a version number:

```php
    $version = $engine->parseVersion('1.5.0-rc.1');
```

On failure to parse a `\RuntimeException` will be thrown.


### Parse Version Range

Again, create an instance of the VersionEngine:

```php
    use ptlis\SemanticVersion\VersionEngine();

    $engine = new VersionEngine();
```

Then call the `parseVersionRange` method passing a version range:

```php
    $version = $engine->parseVersion('1.5.0-3.0.0');
```

As before, on failure to parse a `\RuntimeException` will be thrown.


### Versions

The Version class provides a simple value type representing a semantic version number.

It provides simple accessors for the components of a semantic version number:

```php
    echo $version->getMajor();  // '1'
    echo $version->getMinor();  // '5'
    echo $version->getPatch();  // '0'
    echo $version->getLabel();  // 'rc.1'
```

A `__toString` implementation:

```php
    echo $version;  // '1.5.0-rc.1'
```

Version also implement `SatisfiedByVersionInterface` so offer the `isSatisfiedBy` method which returns true if the versions match.


### Version Ranges

Version ranges are bundles of objects representing a version range constraint, represented by classes implementing `VersionRangeInterface`.

The simplest version range is a version and a comparator (e.g. `>=2.5.3`, '<1.0.0'), which is expressed by the `ComparatorVersion` class.

A more complex range would be one with an upper and lower bound (e.g. `~1.0.0`, '^1.0.0', '>=1.0.0,<2.0.0' which are all equivalent). This is expressed via the `LogicalAnd` class wrapping two `ComparatorVersion` instances.

The most complex constraint would be one containing two ranges (e.g. `1.2.5-2.3.2|^4.0.0`). This is expressed via Wrapping the two classes implementing `VersionRangeInterface`.

As `VersionRangeInterface` extends `SatisfiedByVersionInterface` these implement the `isSatisfiedBy` method which returns true if the passed version satisfies the constraint.


## Known limitations

There currently are a few areas where this library deviates from the specification when dealing with labels & build metadata; this is to due to how the parsing of version-ranges is handled. However, having tested the parsing code against the complete list of packages scraped from packagist I found no packages affected by this.

Example semantic versions which are valid but the library is unable to parse:

* 1.0.0-0.3.7
* 1.0.0-beta-17

