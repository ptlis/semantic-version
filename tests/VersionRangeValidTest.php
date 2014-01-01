<?php

/**
 * Tests to ensure correct error handling for invalid VersionRanges.
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
use ptlis\SemanticVersion\Label\LabelNone;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\VersionRange;

/**
 * Tests to ensure correct behaviour of ComparatorFactory.
 */
class VersionRangeValidTest extends \PHPUnit_Framework_TestCase
{
    public function testLowerAndUpperCompoundSetterRanged()
    {
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelNone());

        $lowerBound = new ComparatorVersion();
        $lowerBound
            ->setComparator(new GreaterThan())
            ->setVersion($lowerVersion);

        $upperVersion = new Version();
        $upperVersion
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelNone());

        $upperBound = new ComparatorVersion();
        $upperBound
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        $versionRange = new VersionRange();
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
            ->setLabel(new LabelNone());

        $lowerBound = new ComparatorVersion();
        $lowerBound
            ->setComparator(new EqualTo())
            ->setVersion($lowerVersion);

        $upperVersion = new Version();
        $upperVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelNone());

        $upperBound = new ComparatorVersion();
        $upperBound
            ->setComparator(new EqualTo())
            ->setVersion($upperVersion);

        $versionRange = new VersionRange();
        $versionRange
            ->setUpperLower($lowerBound, $upperBound);

        $this->assertEquals($lowerBound, $versionRange->getLower());
        $this->assertEquals($upperBound, $versionRange->getUpper());
    }
}
