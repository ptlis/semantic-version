<?php

/**
 * Tests to ensure correct parsing of valid version ranges.
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
use ptlis\SemanticVersion\Entity\VersionRange;
use ptlis\SemanticVersion\VersionEngine;

class ParseVersionRangeValidTest extends \PHPUnit_Framework_TestCase
{
    public function testAllFields()
    {
        $inStr = '>1.0.0<=2.0.0';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>1.0.0<=2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_THAN)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(ComparatorVersion::LESS_OR_EQUAL_TO)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);

        $this->assertEquals($expectVersionRange->getUpper(), $outVersionRange->getUpper());
        $this->assertEquals($expectVersionRange->getLower(), $outVersionRange->getLower());
    }


    public function testBothMajorFullyQualified()
    {
        $inStr = '>1.0.0<=2.0.0';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>1.0.0<=2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_THAN)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(ComparatorVersion::LESS_OR_EQUAL_TO)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testBothMajorMinorFullyQualified()
    {
        $inStr = '>1.0.0<=1.1.0';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>1.0.0<=1.1.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_THAN)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('1')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(ComparatorVersion::LESS_OR_EQUAL_TO)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testBothMajorMinorPatchFullyQualified()
    {
        $inStr = '>1.0.0<=1.0.5';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>1.0.0<=1.0.5';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_THAN)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('5')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(ComparatorVersion::LESS_OR_EQUAL_TO)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testLowerMajorFullyQualified()
    {
        $inStr = '>1.0.0';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>1.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_THAN)
            ->setVersion($lowerVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testUpperMajorFullyQualified()
    {
        $inStr = '<=1.0.0';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '<=1.0.0';

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(ComparatorVersion::LESS_OR_EQUAL_TO)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testSingleMajorPartial()
    {
        $inStr = '1';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>=1.0.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(ComparatorVersion::LESS_THAN)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testSingleMajorMinorPartial()
    {
        $inStr = '1.0';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>=1.0.0<1.1.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('1')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(ComparatorVersion::LESS_THAN)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testSingleMajorMinorPatch()
    {
        $inStr = '1.0.5';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '=1.0.5';

        // Exact
        $exactVersion = new Version();
        $exactVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('5')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $exactComparatorVersion = new ComparatorVersion();
        $exactComparatorVersion
            ->setComparator(ComparatorVersion::EQUAL_TO)
            ->setVersion($exactVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($exactComparatorVersion)
            ->setUpper($exactComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testSingleMajorMinorPatchLabel()
    {
        $inStr = '1.0.5-rc.2';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '=1.0.5-rc.2';

        // Exact
        $exactVersion = new Version();
        $exactVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('5')
            ->setLabel('rc.2')
            ->setLabelNumber(2)
            ->setLabelPrecedence(Version::LABEL_RC);
        $exactComparatorVersion = new ComparatorVersion();
        $exactComparatorVersion
            ->setComparator(ComparatorVersion::EQUAL_TO)
            ->setVersion($exactVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($exactComparatorVersion)
            ->setUpper($exactComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testTildeMajor()
    {
        $inStr = '~1';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>=1.0.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(ComparatorVersion::LESS_THAN)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testTildeMajorMinor()
    {
        $inStr = '~1.7';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>=1.7.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('7')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(ComparatorVersion::LESS_THAN)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testTildeMajorMinorPatch()
    {
        $inStr = '~1.7.9';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>=1.7.9<1.8.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('7')
            ->setPatch('9')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('8')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(ComparatorVersion::LESS_THAN)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testTildeMajorWildcard()
    {
        $inStr = '~1.x';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>=1.0.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(ComparatorVersion::LESS_THAN)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testTildeMajorMinorWildcard()
    {
        $inStr = '~1.7.x';

        $outVersionRange = VersionEngine::parseVersionRange($inStr);

        $expectStr = '>=1.7.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('7')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(ComparatorVersion::LESS_THAN)
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }
}
