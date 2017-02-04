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
use ptlis\SemanticVersion\VersionRange\LogicalOr;

class LogicalOrTest extends TestCase
{
    /**
     * @covers \ptlis\SemanticVersion\VersionRange\LogicalOr
     */
    public function testIsSatisfiedBy()
    {
        $range = new LogicalOr(
            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(1)
                ),
                new ComparatorVersion(
                    new LessThan(),
                    new Version(2)
                )
            ),
            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(3)
                ),
                new ComparatorVersion(
                    new LessThan(),
                    new Version(4)
                )
            )
        );

        $this->assertTrue($range->isSatisfiedBy(new Version(1, 0, 0)));
        $this->assertTrue($range->isSatisfiedBy(new Version(1, 5, 0)));
        $this->assertTrue($range->isSatisfiedBy(new Version(1, 999, 999)));
        $this->assertFalse($range->isSatisfiedBy(new Version(0, 9, 0)));
        $this->assertFalse($range->isSatisfiedBy(new Version(2, 1, 0)));
        $this->assertFalse($range->isSatisfiedBy(new Version(6)));
    }

    /**
     * @covers \ptlis\SemanticVersion\VersionRange\LogicalOr
     */
    public function testToString()
    {
        $range = new LogicalOr(
            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(1)
                ),
                new ComparatorVersion(
                    new LessThan(),
                    new Version(2)
                )
            ),
            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(3)
                ),
                new ComparatorVersion(
                    new LessThan(),
                    new Version(4)
                )
            )
        );

        $this->assertSame('>=1.0.0,<2.0.0|>=3.0.0,<4.0.0', strval($range));
    }
}