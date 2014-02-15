<?php

/**
 * Tests to ensure correct behaviour in valid uses of comparator versions.
 *
 * PHP Version 5.3
 *
 * @copyright   (c) 2014 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test\ComparatorVersion;

use ptlis\SemanticVersion\Version\Comparator\EqualTo;
use ptlis\SemanticVersion\Version\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct behaviour in valid uses of comparator versions.
 */
class ComparatorVersionValidTest extends \PHPUnit_Framework_TestCase
{
    public function testClone()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);

        $comparator1 = new EqualTo();

        $comparatorVersion1 = new ComparatorVersion();
        $comparatorVersion1
            ->setComparator($comparator1)
            ->setVersion($version1);

        $comparatorVersion2 = clone $comparatorVersion1;
        $comparatorVersion2
            ->getVersion()
            ->setMajor(2);
        $comparatorVersion2
            ->setComparator(new GreaterOrEqualTo());

        $this->assertSame($comparatorVersion1->__toString(), '=1.0.0');
        $this->assertSame($comparatorVersion2->__toString(), '>=2.0.0');
    }
}
