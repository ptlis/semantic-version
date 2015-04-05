<?php

/**
 * Tests to ensure correct handling of ComparatorVersion GreaterOrEqualTo comparator.
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
use ptlis\SemanticVersion\ComparatorVersion\Comparator\GreaterOrEqualTo as CompVerGreaterOrEqualTo;
use ptlis\SemanticVersion\OldVersion\Comparator\GreaterThan as VersionGreaterThan;
use ptlis\SemanticVersion\OldVersion\Version;

/**
 * Tests to ensure correct handling of ComparatorVersion GreaterOrEqualTo comparator.
 */
class CompareComparatorVersionGreaterOrEqualToTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSymbol()
    {
        $greaterOrEqualTo = new CompVerGreaterOrEqualTo();
        $this->assertSame('>=', $greaterOrEqualTo->getSymbol());
    }


    public function testToString()
    {
        $greaterOrEqualTo = new CompVerGreaterOrEqualTo();
        $this->assertSame('>=', $greaterOrEqualTo->__toString());
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

        $greaterOrEqualTo = new CompVerGreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($comparatorVersion1, $comparatorVersion2));
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

        $greaterOrEqualTo = new CompVerGreaterOrEqualTo();

        $this->assertFalse($greaterOrEqualTo->compare($comparatorVersion1, $comparatorVersion2));
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

        $greaterOrEqualTo = new CompVerGreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($comparatorVersion1, $comparatorVersion2));
    }
}
