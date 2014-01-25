<?php

/**
 * Tests to ensure correct error handling for valid BoundingPairs.
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

namespace tests\BoundingPair;

use ptlis\SemanticVersion\Comparator\EqualTo;
use ptlis\SemanticVersion\Comparator\GreaterThan;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Label\LabelAbsent;
use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\BoundingPair\BoundingPair;

/**
 * Tests to ensure correct error handling for valid BoundingPairs.
 */
class BoundingPairValidTest extends \PHPUnit_Framework_TestCase
{
    public function testLowerAndUpperCompoundSetterRanged()
    {
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAbsent());

        $lowerBound = new ComparatorVersion();
        $lowerBound
            ->setComparator(new GreaterThan())
            ->setVersion($lowerVersion);

        $upperVersion = new Version();
        $upperVersion
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAbsent());

        $upperBound = new ComparatorVersion();
        $upperBound
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        $versionRange = new BoundingPair();
        $versionRange
            ->setUpperLower($lowerBound, $upperBound);

        $this->assertSame($lowerBound, $versionRange->getLower());
        $this->assertSame($upperBound, $versionRange->getUpper());
    }


    public function testLowerNull()
    {
        $lowerBound = null;

        $upperVersion = new Version();
        $upperVersion
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAbsent());

        $upperBound = new ComparatorVersion();
        $upperBound
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        $versionRange = new BoundingPair();
        $versionRange
            ->setLower($lowerBound)
            ->setUpper($upperBound);

        $this->assertSame($lowerBound, $versionRange->getLower());
        $this->assertSame($upperBound, $versionRange->getUpper());
    }


    public function testUpperNull()
    {
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAbsent());

        $lowerBound = new ComparatorVersion();
        $lowerBound
            ->setComparator(new GreaterThan())
            ->setVersion($lowerVersion);

        $upperBound = null;

        $versionRange = new BoundingPair();
        $versionRange
            ->setLower($lowerBound)
            ->setUpper($upperBound);

        $this->assertSame($lowerBound, $versionRange->getLower());
        $this->assertSame($upperBound, $versionRange->getUpper());
    }


    public function testLowerAndUpperCompoundSetterEqual()
    {
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAbsent());

        $lowerBound = new ComparatorVersion();
        $lowerBound
            ->setComparator(new EqualTo())
            ->setVersion($lowerVersion);

        $upperVersion = new Version();
        $upperVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAbsent());

        $upperBound = new ComparatorVersion();
        $upperBound
            ->setComparator(new EqualTo())
            ->setVersion($upperVersion);

        $versionRange = new BoundingPair();
        $versionRange
            ->setUpperLower($lowerBound, $upperBound);

        $this->assertEquals($lowerBound, $versionRange->getLower());
        $this->assertEquals($upperBound, $versionRange->getUpper());
    }


    public function testCloneOne()
    {
        $versionOne = new Version();
        $versionOne
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelAlpha());
        $comparatorOne = new GreaterThan();
        $comparatorVersionOne = new ComparatorVersion();
        $comparatorVersionOne
            ->setVersion($versionOne)
            ->setComparator($comparatorOne);

        $versionTwo = new Version();
        $versionTwo
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAlpha());
        $comparatorTwo = new LessThan();
        $comparatorVersionTwo = new ComparatorVersion();
        $comparatorVersionTwo
            ->setVersion($versionTwo)
            ->setComparator($comparatorTwo);

        $boundingPairOne = new BoundingPair();
        $boundingPairOne
            ->setLower($comparatorVersionOne)
            ->setUpper($comparatorVersionTwo);

        $boundingPairTwo = clone $boundingPairOne;
        $boundingPairTwo->getLower()->getVersion()->setMajor(3);


        $this->assertNotSame($versionOne, $versionTwo);
        $this->assertSame('1', $boundingPairOne->getLower()->getVersion()->getMajor());
        $this->assertSame('3', $boundingPairTwo->getLower()->getVersion()->getMajor());
    }
}
