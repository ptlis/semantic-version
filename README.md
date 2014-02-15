# SemanticVersion

A modular, extensible library for PHP >= 5.3 providing APIs to support parsing & manipulation of semantic version numbers, comparators, ranges & collections.

The [Semantic Versioning Specification](http://semver.org/) describes the semantics and precedence rules used when parsing & manipulating version numbers etc.

[![Build Status](https://travis-ci.org/ptlis/semantic-version.png?branch=master)](https://travis-ci.org/ptlis/semantic-version) [![Code Coverage](https://scrutinizer-ci.com/g/ptlis/semantic-version/badges/coverage.png?s=fb09ca8f948767518b41f546f33b78fff81b9b71)](https://scrutinizer-ci.com/g/ptlis/semantic-version/) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/ptlis/semantic-version/badges/quality-score.png?s=7651fefac69baed0a2a77d8196dddc48e39f35bd)](https://scrutinizer-ci.com/g/ptlis/semantic-version/) [![Latest Stable Version](https://poser.pugx.org/ptlis/semantic-version/v/stable.png)](https://packagist.org/packages/ptlis/semantic-version)

## Usage

### Parser

```php
    use ptlis\SemanticVersion\VersionEngine();

    $engine = new VersionEngine();

    $version = $engine->parseVersion('1.5.0-rc.1');
    echo $version->getMajor();  // '1'
    echo $version->getMinor();  // '5'
    echo $version->getPatch();  // '0'
    echo $version->getLabel()->getName();  // 'rc'
    echo $version->getLabel()->getVersion();  // '1'
    echo $version->getLabel()->getPrecedence();  // '3'
    echo $version;  // '1.5.0-rc.1'

    $comparatorVersion = $engine->parseComparatorVersion('>=2.0.0');
    echo $comparatorVersion->getComparator()->getSymbol(); // '>='
    $comparatorVersion->getVersion()... // As Version description above
    echo $comparatorVersion; // '>=2.0.0'

    $boundingPair = $engine->parseBoundingPair('>=1.0.0<2.0.0');
    $boundingPair->getUpper()... // As ComparatorVersion description above
    $boundingPair->getLower()... // As ComparatorVersion description above
```

## TODO

* Collections - probably require a factory of their own, for parsing etc

## Known limitations

There currently are a few areas where this library deviates from the specification when dealing with labels & build metadata; this is to due to how the parsing of version-ranges is handled. However, having tested the parsing code against the complete list of packages scraped from packagist I found no packages affected by this.

Example semantic versions which are valid but the library is unable to parse:

* 1.0.0-0.3.7
* 1.0.0-beta-17

