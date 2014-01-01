<?php

/**
 * Tests to ensure correct parsing of malformed but usable ranged versions.
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

use ptlis\SemanticVersion\Entity\RangedVersion;
use ptlis\SemanticVersion\Entity\Version;
use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct parsing of malformed but usable ranged versions.
 */
class ParseRangedVersionMalformedTest extends \PHPUnit_Framework_TestCase
{
    public function testTrailingMajorDot()
    {
        $inStr = '>=1.';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '>=1.0.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testTrailingMinorDot()
    {
        $inStr = '>=1.5.';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '>=1.5.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testTrailingLabelHyphen()
    {
        $inStr = '>=1.5.0-';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '>=1.5.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testOmittedLabelNumberDot()
    {
        $inStr = '>=1.5.0-rc1';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '>=1.5.0-rc1';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel('rc1')
            ->setLabelNumber(1)
            ->setLabelPrecedence(Version::LABEL_RC);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }
}
