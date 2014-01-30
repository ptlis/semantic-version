<?php

/**
 * Tests to ensure correct parsing of malformed bounding pairs.
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

namespace tests\ParseBoundPair;

use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Label\LabelAbsent;
use ptlis\SemanticVersion\Label\LabelRc;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\BoundingPair\BoundingPair;
use ptlis\SemanticVersion\VersionEngine;

/*
 * Tests to ensure correct parsing of malformed bounding pairs.
 */
class ParseBoundingPairMalformedTest extends \PHPUnit_Framework_TestCase
{
    public function testTrailingMajorDot()
    {
        $inStr = '>=1.<=1.2.0';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>=1.0.0<=1.2.0';

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
            ->setMajor('1')
            ->setMinor('2')
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


    public function testTrailingMinorDot()
    {
        $inStr = '>=1.0.<=1.2.0';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>=1.0.0<=1.2.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterOrEqualTo)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('2')
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


    public function testTrailingLabelHyphen()
    {
        $inStr = '>=1.0.0-<=1.2.0';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>=1.0.0<=1.2.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelAbsent());
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterOrEqualTo)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('2')
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


    public function testOmittedLabelNumberDot()
    {
        $inStr = '>=1.0.0-rc1<=1.2.0';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>=1.0.0-rc.1<=1.2.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('0')
            ->setLabel(new LabelRc(1));
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterOrEqualTo)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('1')
            ->setMinor('2')
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


    public function testComparatorFlipped()
    {
        $inStr = '<=2.5.0>=1.0.5';

        $engine  = new VersionEngine();
        $outBoundingPair = $engine->parseBoundingPair($inStr);

        $expectStr = '>=1.0.5<=2.5.0';

        // Lower
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor('1')
            ->setMinor('0')
            ->setPatch('5')
            ->setLabel(new LabelAbsent());
        $lowerComparatorVersion = new ComparatorVersion();
        $lowerComparatorVersion
            ->setComparator(new GreaterOrEqualTo)
            ->setVersion($lowerVersion);

        // Upper
        $upperVersion = new Version();
        $upperVersion
            ->setMajor('2')
            ->setMinor('5')
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
}
