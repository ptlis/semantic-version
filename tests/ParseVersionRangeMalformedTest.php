<?php

/**
 * Tests to ensure correct parsing of malformed version ranges.
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
use ptlis\SemanticVersion\Entity\VersionRange;
use ptlis\SemanticVersion\VersionEngine;

/*
 * Tests to ensure correct parsing of malformed version ranges.
 */
class ParseVersionRangeMalformedTest extends \PHPUnit_Framework_TestCase
{
    public function testTrailingMajorDot()
    {
        $inStr = '>=1.<=1.2.0';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>=1.0.0<=1.2.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerRangedVersion = new RangedVersion();
        $lowerRangedVersion
            ->setComparator(RangedVersion::GREATER_OR_EQUAL_TO)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('2')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperRangedVersion = new RangedVersion();
        $upperRangedVersion
            ->setComparator(RangedVersion::LESS_OR_EQUAL_TO)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerRangedVersion)
            ->setUpper($upperRangedVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testTrailingMinorDot()
    {
        $inStr = '>=1.0.<=1.2.0';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>=1.0.0<=1.2.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerRangedVersion = new RangedVersion();
        $lowerRangedVersion
            ->setComparator(RangedVersion::GREATER_OR_EQUAL_TO)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('2')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperRangedVersion = new RangedVersion();
        $upperRangedVersion
            ->setComparator(RangedVersion::LESS_OR_EQUAL_TO)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerRangedVersion)
            ->setUpper($upperRangedVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testTrailingLabelHyphen()
    {
        $inStr = '>=1.0.0-<=1.2.0';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>=1.0.0<=1.2.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerRangedVersion = new RangedVersion();
        $lowerRangedVersion
            ->setComparator(RangedVersion::GREATER_OR_EQUAL_TO)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('2')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperRangedVersion = new RangedVersion();
        $upperRangedVersion
            ->setComparator(RangedVersion::LESS_OR_EQUAL_TO)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerRangedVersion)
            ->setUpper($upperRangedVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testOmittedLabelNumberDot()
    {
        $inStr = '>=1.0.0-rc1<=1.2.0';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>=1.0.0-rc1<=1.2.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel('rc1')
            ->setLabelNumber(1)
            ->setLabelPrecedence(Version::LABEL_RC);
        $lowerRangedVersion = new RangedVersion();
        $lowerRangedVersion
            ->setComparator(RangedVersion::GREATER_OR_EQUAL_TO)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('2')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperRangedVersion = new RangedVersion();
        $upperRangedVersion
            ->setComparator(RangedVersion::LESS_OR_EQUAL_TO)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerRangedVersion)
            ->setUpper($upperRangedVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }
}
