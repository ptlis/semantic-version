<?php

/**
 * Tests to ensure correct handling of ComparatorVersion equality comparator.
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
use ptlis\SemanticVersion\ComparatorVersion\Comparator\EqualTo as CompVerEqualTo;
use ptlis\SemanticVersion\Version\Comparator\GreaterThan as VersionGreaterThan;
use ptlis\SemanticVersion\Version\Comparator\LessThan as VersionLessThan;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of ComparatorVersion equality comparator.
 */
class CompareVersionEqualityTest extends \PHPUnit_Framework_TestCase
{
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

        $equalTo = new CompVerEqualTo();

        $this->assertSame('=', $equalTo->getSymbol());
        $this->assertTrue($equalTo->compare($comparatorVersion1, $comparatorVersion2));
    }


    public function testNotEqualComparator()
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
            ->setComparator(new VersionLessThan())
            ->setVersion($version2);

        $equalTo = new CompVerEqualTo();

        $this->assertFalse($equalTo->compare($comparatorVersion1, $comparatorVersion2));
    }


    public function testNotEqualVersion()
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
            ->setMinor(5)
            ->setPatch(0);
        $comparatorVersion2 = new ComparatorVersion();
        $comparatorVersion2
            ->setComparator(new VersionGreaterThan())
            ->setVersion($version2);

        $equalTo = new CompVerEqualTo();

        $this->assertFalse($equalTo->compare($comparatorVersion1, $comparatorVersion2));
    }
}
