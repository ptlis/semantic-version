<?php

/**
 * Tests to ensure correct handling of VersionRange::isSatisfiedBy where comparators are EqualTo
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

use ptlis\SemanticVersion\Comparator\EqualTo;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\VersionRange;

class InRangeVersionRangeEqualToTest extends \PHPUnit_Framework_TestCase
{
    public function testEquality()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha(3));

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new EqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setLower($comparatorVersion)
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha(3));

        $this->assertTrue($versionRange->isSatisfiedBy($version2));
    }


    public function testNotEqualityMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha(3));

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new EqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setLower($comparatorVersion)
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha(3));

        $this->assertFalse($versionRange->isSatisfiedBy($version2));
    }


    public function testNotEqualityMinor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(2)
            ->setPatch(15)
            ->setLabel(new LabelAlpha(3));

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new EqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setLower($comparatorVersion)
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha(3));

        $this->assertFalse($versionRange->isSatisfiedBy($version2));
    }


    public function testNotEqualityPatch()
    {
        $version1 = new Version();
        $version1
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(5)
            ->setLabel(new LabelAlpha(3));

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new EqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setLower($comparatorVersion)
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha(3));

        $this->assertFalse($versionRange->isSatisfiedBy($version2));
    }


    public function testNotEqualityLabelName()
    {
        $version1 = new Version();
        $version1
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(5)
            ->setLabel(new LabelBeta(3));

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new EqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setLower($comparatorVersion)
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha(3));

        $this->assertFalse($versionRange->isSatisfiedBy($version2));
    }


    public function testNotEqualityLabelNumber()
    {
        $version1 = new Version();
        $version1
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(5)
            ->setLabel(new LabelAlpha(3));

        $comparatorVersion = new ComparatorVersion();
        $comparatorVersion
            ->setComparator(new EqualTo())
            ->setVersion($version1);

        $versionRange = new VersionRange();
        $versionRange
            ->setLower($comparatorVersion)
            ->setUpper($comparatorVersion);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(15)
            ->setLabel(new LabelAlpha(4));

        $this->assertFalse($versionRange->isSatisfiedBy($version2));
    }
}
