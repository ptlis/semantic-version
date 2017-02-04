<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test\VersionRange;

use PHPUnit\Framework\TestCase;
use ptlis\SemanticVersion\Comparator\GreaterThan;
use ptlis\SemanticVersion\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;

class ComparatorVersionTest extends TestCase
{
    /**
     * @covers \ptlis\SemanticVersion\VersionRange\ComparatorVersion
     */
    public function testIsSatisfiedBy()
    {
        $comparatorVersion = new ComparatorVersion(
            new GreaterThan(),
            new Version(1, 3, 7)
        );

        $this->assertTrue($comparatorVersion->isSatisfiedBy(new Version(1, 4)));
        $this->assertFalse($comparatorVersion->isSatisfiedBy(new Version(1, 2)));
    }

    /**
     * @covers \ptlis\SemanticVersion\VersionRange\ComparatorVersion
     */
    public function testToString()
    {
        $comparatorVersion = new ComparatorVersion(
            new LessOrEqualTo(),
            new Version(2, 5, 1)
        );

        $this->assertEquals('<=2.5.1', strval($comparatorVersion));
    }

    /**
     * @covers \ptlis\SemanticVersion\VersionRange\ComparatorVersion
     */
    public function testGetters()
    {

        $comparatorVersion = new ComparatorVersion(
            new LessOrEqualTo(),
            new Version(2, 5, 1)
        );

        $this->assertEquals(new LessOrEqualTo(), $comparatorVersion->getComparator());
        $this->assertEquals(new Version(2, 5, 1), $comparatorVersion->getVersion());
    }
}