<?php

/**
 * Tests to ensure correct parsing of valid bounding pairs.
 *
 * PHP Version 5.3
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

namespace ptlis\SemanticVersion\Test\ParseBoundPair;

use ptlis\SemanticVersion\Version\Comparator\EqualTo;
use ptlis\SemanticVersion\Version\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Version\Comparator\GreaterThan;
use ptlis\SemanticVersion\Version\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Version\Comparator\LessThan;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Label\LabelAbsent;
use ptlis\SemanticVersion\Label\LabelRc;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\BoundingPair\BoundingPair;
use ptlis\SemanticVersion\VersionEngine;

class ParseBoundingPairValidTest extends \PHPUnit_Framework_TestCase
{
    public function testAllFields()
    {
        $inStr = '>1.0.0<=2.0.0';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>1.0.0<=2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
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
            ->setLabel(new LabelAbsent());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($upperVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);

        $this->assertEquals($expectBoundingPair->getUpper(), $outBoundingPair->getUpper());
        $this->assertEquals($expectBoundingPair->getLower(), $outBoundingPair->getLower());
    }


    public function testBothMajorFullyQualified()
    {
        $inStr = '>1.0.0<=2.0.0';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>1.0.0<=2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
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
            ->setLabel(new LabelAbsent());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($upperVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testBothMajorMinorFullyQualified()
    {
        $inStr = '>1.0.0<=1.1.0';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>1.0.0<=1.1.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
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
            ->setLabel(new LabelAbsent());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($upperVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testBothMajorMinorPatchFullyQualified()
    {
        $inStr = '>1.0.0<=1.0.5';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>1.0.0<=1.0.5';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
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
            ->setLabel(new LabelAbsent());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($upperVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testLowerMajorFullyQualified()
    {
        $inStr = '>1.0.0';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>1.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterThan())
            ->setVersion($lowerVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($lowerComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testUpperMajorFullyQualified()
    {
        $inStr = '<=1.0.0';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '<=1.0.0';

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($upperVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testSingleMajorPartial()
    {
        $inStr = '1';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '=1.0.0';

        // Lower
        $version = new Version();
        $version
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new EqualTo())
            ->setVersion($version);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($comparatorVersion)
            ->setUpper($comparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testSingleMajorMinorPartial()
    {
        $inStr = '1.3';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '=1.3.0';

        // Exact
        $exactVersion = new Version();
        $exactVersion
            ->setMajor('1')
            ->setMinor('3')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
        $exactComparatorVersion = new ComparatorVersion();
        $exactComparatorVersion
            ->setComparator(new EqualTo())
            ->setVersion($exactVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($exactComparatorVersion)
            ->setUpper($exactComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testSingleMajorMinorPatch()
    {
        $inStr = '1.0.5';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '=1.0.5';

        // Exact
        $exactVersion = new Version();
        $exactVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('5')
            ->setLabel(new LabelAbsent());
        $exactComparatorVersion = new ComparatorVersion();
        $exactComparatorVersion
            ->setComparator(new EqualTo())
            ->setVersion($exactVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($exactComparatorVersion)
            ->setUpper($exactComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testSingleMajorMinorPatchLabel()
    {
        $inStr = '1.0.5-rc.2';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '=1.0.5-rc.2';

        // Exact
        $exactVersion = new Version();
        $exactVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('5')
            ->setLabel(new LabelRc(2));
        $exactComparatorVersion = new ComparatorVersion();
        $exactComparatorVersion
            ->setComparator(new EqualTo())
            ->setVersion($exactVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($exactComparatorVersion)
            ->setUpper($exactComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testTildeMajor()
    {
        $inStr = '~1';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>=1.0.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
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
            ->setLabel(new LabelAbsent());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testTildeMajorMinor()
    {
        $inStr = '~1.7';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>=1.7.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('7')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
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
            ->setLabel(new LabelAbsent());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testTildeMajorMinorPatch()
    {
        $inStr = '~1.7.9';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>=1.7.9<1.8.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('7')
            ->setPatch('9')
            ->setLabel(new LabelAbsent());
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
            ->setLabel(new LabelAbsent());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testTildeMajorWildcard()
    {
        $inStr = '~1.x';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>=1.0.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
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
            ->setLabel(new LabelAbsent());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testTildeMajorMinorWildcard()
    {
        $inStr = '~1.7.x';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>=1.7.0<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('7')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
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
            ->setLabel(new LabelAbsent());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testHyphenated()
    {
        $inStr = '1.5.3-3.0.0';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>=1.5.3<3.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('5')
            ->setPatch('3')
            ->setLabel(new LabelAbsent());
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
            ->setLabel(new LabelAbsent());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testHyphenatedLabel()
    {
        $inStr = '2.1.3-beta.1-3.0.0';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

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
            ->setLabel(new LabelAbsent());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }


    public function testBuildMetadata()
    {
        $inStr = '1.4.0-alpha.1+2014-01-13-2.0.0';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>=1.4.0-alpha.1+2014-01-13<2.0.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('4')
            ->setPatch('0')
            ->setLabel(new LabelAlpha(1, '2014-01-13'));
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
            ->setLabel(new LabelAbsent());
        $upperComparatorVersion = new ComparatorVersion();
        $upperComparatorVersion
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        // Range
        $expectBoundingPair = new BoundingPair();
        $expectBoundingPair
            ->setLower($lowerComparatorVersion)
            ->setUpper($upperComparatorVersion);

        $this->assertSame($expectStr, $outBoundingPair->__toString());
        $this->assertEquals($expectBoundingPair, $outBoundingPair);
    }
}
