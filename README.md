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

    $comparatorRange = $engine->parseVersionRange('>=1.0.0<2.0.0');
    $comparatorRange->getUpper()... // As VersionRange description above
    $comparatorRange->getLower()... // As VersionRange description above
```

## TODO

* Sort
* Handle hyphenated ranges (eg "1.2 - 1.4")
