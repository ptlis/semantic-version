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

## TODO

* Sort
* test for label omitting hypen
