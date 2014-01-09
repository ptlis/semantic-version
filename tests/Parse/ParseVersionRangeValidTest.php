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

namespace tests\Parse;

use ptlis\SemanticVersion\Comparator\EqualTo;
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\GreaterThan;
use ptlis\SemanticVersion\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Label\LabelNone;
use ptlis\SemanticVersion\Label\LabelRc;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\VersionRange;
use ptlis\SemanticVersion\VersionEngine;

class ParseVersionRangeValidTest extends \PHPUnit_Framework_TestCase
{
    public function testAllFields()
    {
        $inStr = '>1.0.0<=2.0.0';

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>1.0.0<=2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());;
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterThan())
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());;
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessOrEqualTo())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>1.0.0<=2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());;
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterThan())
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());;
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessOrEqualTo())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>1.0.0<=1.1.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());;
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterThan())
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('1')
            ->setPatch('0')
            ->setLabel(new LabelNone());;
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessOrEqualTo())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>1.0.0<=1.0.5';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());;
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterThan())
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('5')
            ->setLabel(new LabelNone());;
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessOrEqualTo())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>1.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());;
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterThan())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '<=1.0.0';

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());;
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessOrEqualTo())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>=1.0.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());;
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());;
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>=1.0.0<1.1.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());;
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('1')
            ->setPatch('0')
            ->setLabel(new LabelNone());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '=1.0.5';

        // Exact
        $exactVersion = new Version();
        $exactVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('5')
            ->setLabel(new LabelNone());;
        $exactComparatorVersion = new ComparatorVersion();
        $exactComparatorVersion
            ->setComparator(new EqualTo())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '=1.0.5-rc.2';

        // Exact
        $exactVersion = new Version();
        $exactVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('5')
            ->setLabel(new LabelRc(2));;
        $exactComparatorVersion = new ComparatorVersion();
        $exactComparatorVersion
            ->setComparator(new EqualTo())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>=1.0.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>=1.7.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('7')
            ->setPatch('0')
            ->setLabel(new LabelNone());
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>=1.7.9<1.8.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('7')
            ->setPatch('9')
            ->setLabel(new LabelNone());
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('8')
            ->setPatch('0')
            ->setLabel(new LabelNone());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>=1.0.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
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

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>=1.7.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('7')
            ->setPatch('0')
            ->setLabel(new LabelNone());
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testHyphenated()
    {
        $inStr = '1.5.3-3.0.0';

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>=1.5.3<3.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('5')
            ->setPatch('3')
            ->setLabel(new LabelNone());
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('3')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        // Range
        $expectVersionRange = new VersionRange();
        $expectVersionRange
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outVersionRange->__toString());
        $this->assertEquals($expectVersionRange, $outVersionRange);
    }


    public function testHyphenatedLabel()
    {
        $inStr = '2.1.3-beta.1-3.0.0';

        $engine  = new VersionEngine();
        $outVersionRange = $engine->parseVersionRange($inStr);

        $expectStr = '>=2.1.3-beta.1<3.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('2')
            ->setMinor('1')
            ->setPatch('3')
            ->setLabel(new LabelBeta(1));
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('3')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelNone());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
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
