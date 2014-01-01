<?php

/**
 * Tests to ensure correct handling of VersionRange::isSatisfiedBy where upper comparator is LessOrEqualTo.
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

use ptlis\SemanticVersion\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\VersionRange;

class InRangeVersionRangeLessOrEqualToTest extends \PHPUnit_Framework_TestCase
{
    public function testEqualTo()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha(3));

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha(3));

        $this->assertTrue($versionRange->isSatisfiedBy($version2));
    }


    public function testLessThanMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(2);

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1);

        $this->assertTrue($versionRange->isSatisfiedBy($version2));
    }


    public function testGreaterThanMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1);

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(2);

        $this->assertFalse($versionRange->isSatisfiedBy($version2));
    }


    public function testLessThanMinor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(5);

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0);

        $this->assertTrue($versionRange->isSatisfiedBy($version2));
    }


    public function testGreaterThanMinor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0);

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(5);

        $this->assertFalse($versionRange->isSatisfiedBy($version2));
    }


    public function testLessThanPatch()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(18);

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15);

        $this->assertTrue($versionRange->isSatisfiedBy($version2));
    }


    public function testGreaterThanPatch()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15);

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(16);

        $this->assertFalse($versionRange->isSatisfiedBy($version2));
    }


    public function testLessThanLabelName()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelBeta());

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha());

        $this->assertTrue($versionRange->isSatisfiedBy($version2));
    }


    public function testGreaterThanLabelName()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha());

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelBeta());

        $this->assertFalse($versionRange->isSatisfiedBy($version2));
    }


    public function testLessThanLabelVersion()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha(3));

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha());

        $this->assertTrue($versionRange->isSatisfiedBy($version2));
    }


    public function testGreaterThanLabelVersion()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha());

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha(3));

        $this->assertFalse($versionRange->isSatisfiedBy($version2));
    }
}
