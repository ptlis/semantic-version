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
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;

class LogicalAndTest extends TestCase
{
    /**
     * @covers \ptlis\SemanticVersion\VersionRange\LogicalAnd
     */
    public function testIsSatisfiedBy()
    {
        $range = new LogicalAnd(
            new ComparatorVersion(
                new GreaterOrEqualTo(),
                new Version(5)
            ),
            new ComparatorVersion(
                new LessThan(),
                new Version(6)
            )
        );

        $this->assertTrue($range->isSatisfiedBy(new Version(5, 0, 0)));
        $this->assertTrue($range->isSatisfiedBy(new Version(5, 5, 0)));
        $this->assertTrue($range->isSatisfiedBy(new Version(5, 999, 999)));
        $this->assertFalse($range->isSatisfiedBy(new Version(4)));
        $this->assertFalse($range->isSatisfiedBy(new Version(6)));
    }

    /**
     * @covers \ptlis\SemanticVersion\VersionRange\LogicalAnd
     */
    public function testToString()
    {
        $range = new LogicalAnd(
            new ComparatorVersion(
                new GreaterOrEqualTo(),
                new Version(1, 2, 7)
            ),
            new ComparatorVersion(
                new LessThan(),
                new Version(3)
            )
        );

        $this->assertSame('>=1.2.7,<3.0.0', strval($range));
    }
}