<?php

/**
 * Tests to ensure correct handling of VersionRange::isSatisfiedBy where both upper & lower are provided.
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

namespace tests\InRange;

use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Label\LabelNone;
use ptlis\SemanticVersion\Label\LabelRc;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\VersionRange;

class InRangeVersionRangeRangedTest extends \PHPUnit_Framework_TestCase
{
    public function testInRangeMajor()
    {
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $lowerBound = new ComparatorVersion();
        $lowerBound
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        $upperVersion = new Version();
        $upperVersion
            ->setMajor(3)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $upperBound = new ComparatorVersion();
        $upperBound
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        $versionRange = new VersionRange();
        $versionRange
            ->setLower($lowerBound)
            ->setUpper($upperBound);

        $testVersion = new Version();
        $testVersion
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertTrue($versionRange->isSatisfiedBy($testVersion));
    }


    public function testInRangeMinor()
    {
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $lowerBound = new ComparatorVersion();
        $lowerBound
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        $upperVersion = new Version();
        $upperVersion
            ->setMajor(1)
            ->setMinor(8)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $upperBound = new ComparatorVersion();
        $upperBound
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        $versionRange = new VersionRange();
        $versionRange
            ->setLower($lowerBound)
            ->setUpper($upperBound);

        $testVersion = new Version();
        $testVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertTrue($versionRange->isSatisfiedBy($testVersion));
    }


    public function testInRangePatch()
    {
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(5)
            ->setLabel(new LabelNone());

        $lowerBound = new ComparatorVersion();
        $lowerBound
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        $upperVersion = new Version();
        $upperVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(9)
            ->setLabel(new LabelNone());

        $upperBound = new ComparatorVersion();
        $upperBound
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        $versionRange = new VersionRange();
        $versionRange
            ->setLower($lowerBound)
            ->setUpper($upperBound);

        $testVersion = new Version();
        $testVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(6)
            ->setLabel(new LabelNone());

        $this->assertTrue($versionRange->isSatisfiedBy($testVersion));
    }


    public function testInRangeLabelName()
    {
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelBeta());

        $lowerBound = new ComparatorVersion();
        $lowerBound
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        $upperVersion = new Version();
        $upperVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $upperBound = new ComparatorVersion();
        $upperBound
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        $versionRange = new VersionRange();
        $versionRange
            ->setLower($lowerBound)
            ->setUpper($upperBound);

        $testVersion = new Version();
        $testVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc());

        $this->assertTrue($versionRange->isSatisfiedBy($testVersion));
    }


    public function testInRangeLabelNumber()
    {
        $lowerVersion = new Version();
        $lowerVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc(3));

        $lowerBound = new ComparatorVersion();
        $lowerBound
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion($lowerVersion);

        $upperVersion = new Version();
        $upperVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc(5));

        $upperBound = new ComparatorVersion();
        $upperBound
            ->setComparator(new LessThan())
            ->setVersion($upperVersion);

        $versionRange = new VersionRange();
        $versionRange
            ->setLower($lowerBound)
            ->setUpper($upperBound);

        $testVersion = new Version();
        $testVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc(3));

        $this->assertTrue($versionRange->isSatisfiedBy($testVersion));
    }
}
