<?php

/**
 * Tests to ensure correct handling of ComparatorVersion LessThan comparator.
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

namespace ptlis\SemanticVersion\Test\ComparatorVersion\Comparator;

use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\ComparatorVersion\Comparator\GreaterThan as CompVerGreaterThan;
use ptlis\SemanticVersion\Version\Comparator\GreaterThan as VersionGreaterThan;
use ptlis\SemanticVersion\Version\Comparator\GreaterOrEqualTo as VersionGreaterOrEqualTo;
use ptlis\SemanticVersion\Version\Comparator\LessThan as VersionLessThan;
use ptlis\SemanticVersion\Version\Comparator\LessOrEqualTo as VersionLessOrEqualTo;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of ComparatorVersion LessThan comparator.
 */
class CompareComparatorVersionGreaterThanTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSymbol()
    {
        $greaterThan = new CompVerGreaterThan();
        $this->assertSame('>', $greaterThan->getSymbol());
    }


    public function testToString()
    {
        $greaterThan = new CompVerGreaterThan();
        $this->assertSame('>', $greaterThan->__toString());
    }


    public function testEqual()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion1 = new ComparatorVersion();
        $comparatorVersion1
            ->setComparator(new VersionGreaterThan())
            ->setVersion($version1);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion2 = new ComparatorVersion();
        $comparatorVersion2
            ->setComparator(new VersionGreaterThan())
            ->setVersion($version2);

        $greaterThan = new CompVerGreaterThan();

        $this->assertFalse($greaterThan->compare($comparatorVersion1, $comparatorVersion2));
    }


    public function testGreaterThanVersion()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(1)
            ->setPatch(0);
        $comparatorVersion1 = new ComparatorVersion();
        $comparatorVersion1
            ->setComparator(new VersionGreaterThan())
            ->setVersion($version1);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion2 = new ComparatorVersion();
        $comparatorVersion2
            ->setComparator(new VersionGreaterThan())
            ->setVersion($version2);

        $greaterThan = new CompVerGreaterThan();

        $this->assertTrue($greaterThan->compare($comparatorVersion1, $comparatorVersion2));
    }


    public function testLessThanVersion()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion1 = new ComparatorVersion();
        $comparatorVersion1
            ->setComparator(new VersionGreaterThan())
            ->setVersion($version1);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(1)
            ->setPatch(0);
        $comparatorVersion2 = new ComparatorVersion();
        $comparatorVersion2
            ->setComparator(new VersionGreaterThan())
            ->setVersion($version2);

        $greaterThan = new CompVerGreaterThan();

        $this->assertFalse($greaterThan->compare($comparatorVersion1, $comparatorVersion2));
    }


    public function testGreaterThanComparatorOne()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion1 = new ComparatorVersion();
        $comparatorVersion1
            ->setComparator(new VersionLessOrEqualTo())
            ->setVersion($version1);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion2 = new ComparatorVersion();
        $comparatorVersion2
            ->setComparator(new VersionLessThan())
            ->setVersion($version2);

        $greaterThan = new CompVerGreaterThan();

        $this->assertTrue($greaterThan->compare($comparatorVersion1, $comparatorVersion2));
    }


    public function testGreaterThanComparatorTwo()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion1 = new ComparatorVersion();
        $comparatorVersion1
            ->setComparator(new VersionGreaterOrEqualTo())
            ->setVersion($version1);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion2 = new ComparatorVersion();
        $comparatorVersion2
            ->setComparator(new VersionLessOrEqualTo())
            ->setVersion($version2);

        $greaterThan = new CompVerGreaterThan();

        $this->assertTrue($greaterThan->compare($comparatorVersion1, $comparatorVersion2));
    }


    public function testGreaterThanComparatorThree()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion1 = new ComparatorVersion();
        $comparatorVersion1
            ->setComparator(new VersionGreaterThan())
            ->setVersion($version1);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion2 = new ComparatorVersion();
        $comparatorVersion2
            ->setComparator(new VersionGreaterOrEqualTo())
            ->setVersion($version2);

        $greaterThan = new CompVerGreaterThan();

        $this->assertTrue($greaterThan->compare($comparatorVersion1, $comparatorVersion2));
    }


    public function testLessThanComparatorOne()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion1 = new ComparatorVersion();
        $comparatorVersion1
            ->setComparator(new VersionLessThan())
            ->setVersion($version1);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion2 = new ComparatorVersion();
        $comparatorVersion2
            ->setComparator(new VersionLessOrEqualTo())
            ->setVersion($version2);

        $greaterThan = new CompVerGreaterThan();

        $this->assertFalse($greaterThan->compare($comparatorVersion1, $comparatorVersion2));
    }


    public function testLessThanComparatorTwo()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion1 = new ComparatorVersion();
        $comparatorVersion1
            ->setComparator(new VersionLessOrEqualTo())
            ->setVersion($version1);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion2 = new ComparatorVersion();
        $comparatorVersion2
            ->setComparator(new VersionGreaterOrEqualTo())
            ->setVersion($version2);

        $greaterThan = new CompVerGreaterThan();

        $this->assertFalse($greaterThan->compare($comparatorVersion1, $comparatorVersion2));
    }


    public function testLessThanComparatorThree()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion1 = new ComparatorVersion();
        $comparatorVersion1
            ->setComparator(new VersionGreaterOrEqualTo())
            ->setVersion($version1);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);
        $comparatorVersion2 = new ComparatorVersion();
        $comparatorVersion2
            ->setComparator(new VersionGreaterThan())
            ->setVersion($version2);

        $greaterThan = new CompVerGreaterThan();

        $this->assertFalse($greaterThan->compare($comparatorVersion1, $comparatorVersion2));
    }
}