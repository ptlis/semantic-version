# SemanticVersion

A library offering API to support working with semantic version numbers.

See the [Semantic Versioning](http://semver.org/) website for the specification of a "semantic version".

```php
    use ptlis\SemanticVersion\VersionEngine();

    $engine = new VersionEngine();

    $version = $engine->parseVersion('1.5.0-rc.1');
    echo $version->getMajor();  // 1
    echo $version->getMinor();  // 5
    echo $version->getPatch();  // 0
    echo $version->getLabel()->getName();  // 'rc'
    echo $version->getLabel()->getVersion();  // 1
    echo $version->getLabel()->getPrecedence();  // 3

    $comparatorVersion = $engine->parseComparatorVersion('>=2.0.0');
    echo $comparatorVersion->getComparator()->getSymbol(); // '>='
    $comparatorVersion->getVersion()... // As Version description above

    $boundingPair = $engine->parseBoundingPair('>=1.0.0<2.0.0');
    $boundingPair->getUpper()... // As ComparatorVersion description above
    $boundingPair->getLower()... // As ComparatorVersion description above
```

[![Build Status](https://travis-ci.org/ptlis/semantic-version.png?branch=master)](https://travis-ci.org/ptlis/semantic-version)

[![Code Coverage](https://scrutinizer-ci.com/g/ptlis/semantic-version/badges/coverage.png?s=fb09ca8f948767518b41f546f33b78fff81b9b71)](https://scrutinizer-ci.com/g/ptlis/semantic-version/)

## TODO

* Sort
* Pre-release labels that consist of numbers & periods.
