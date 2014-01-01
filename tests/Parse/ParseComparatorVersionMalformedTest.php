<?php

/**
 * Tests to ensure correct parsing of malformed but usable comparator versions.
 *
 * PHP Version 5.4
 *
 * Based off the tests for vierbergenlars\SemVar https://github.com/vierbergenlars/php-semver/
 *
 * @copyright   (c) 2014 Brian Ridley
 * @author      Brian Ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\Parse;

use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Label\LabelNone;
use ptlis\SemanticVersion\Label\LabelRc;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct parsing of malformed but usable comparator versions.
 */
class ParseComparatorVersionMalformedTest extends \PHPUnit_Framework_TestCase
{
    public function testTrailingMajorDot()
    {
        $inStr = '>=1.';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>=1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testTrailingMinorDot()
    {
        $inStr = '>=1.5.';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>=1.5.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testTrailingLabelHyphen()
    {
        $inStr = '>=1.5.0-';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>=1.5.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testOmittedLabelNumberDot()
    {
        $inStr = '>=1.5.0-rc1';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>=1.5.0-rc.1';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelRc(1));

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }
}
