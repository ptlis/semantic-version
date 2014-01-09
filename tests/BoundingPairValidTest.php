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

namespace tests;

use ptlis\SemanticVersion\Comparator\EqualTo;
use ptlis\SemanticVersion\Comparator\GreaterThan;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Label\LabelAbsent;
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
}
