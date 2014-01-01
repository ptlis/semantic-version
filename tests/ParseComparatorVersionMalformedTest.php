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

namespace tests;

use ptlis\SemanticVersion\Entity\ComparatorVersion;
use ptlis\SemanticVersion\Entity\Version;
use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct parsing of malformed but usable comparator versions.
 */
class ParseComparatorVersionMalformedTest extends \PHPUnit_Framework_TestCase
{
    public function testTrailingMajorDot()
    {
        $inStr = '>=1.';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '>=1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testTrailingMinorDot()
    {
        $inStr = '>=1.5.';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '>=1.5.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testTrailingLabelHyphen()
    {
        $inStr = '>=1.5.0-';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '>=1.5.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testOmittedLabelNumberDot()
    {
        $inStr = '>=1.5.0-rc1';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '>=1.5.0-rc1';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel('rc1')
            ->setLabelNumber(1)
            ->setLabelPrecedence(Version::LABEL_RC);

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }
}
