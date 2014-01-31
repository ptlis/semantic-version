<?php

/**
 * Tests to ensure correct handling of ComparatorVersion LessOrEqualTo comparator.
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

namespace tests\ComparatorVersion\Comparator;

use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\ComparatorVersion\Comparator\LessOrEqualTo as CompVerLessOrEqualTo;
use ptlis\SemanticVersion\Version\Comparator\GreaterThan as VersionGreaterThan;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of ComparatorVersion LessThan comparator.
 */
class CompareComparatorVersionLessOrEqualToTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSymbol()
    {
        $lessOrEqualTo = new CompVerLessOrEqualTo();
        $this->assertSame('<=', $lessOrEqualTo->getSymbol());
    }


    public function testToString()
    {
        $lessOrEqualTo = new CompVerLessOrEqualTo();
        $this->assertSame('<=', $lessOrEqualTo->__toString());
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

        $lessOrEqualTo = new CompVerLessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($comparatorVersion1, $comparatorVersion2));
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

        $lessOrEqualTo = new CompVerLessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($comparatorVersion1, $comparatorVersion2));
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

        $lessOrEqualTo = new CompVerLessOrEqualTo();

        $this->assertFalse($lessOrEqualTo->compare($comparatorVersion1, $comparatorVersion2));
    }
}
