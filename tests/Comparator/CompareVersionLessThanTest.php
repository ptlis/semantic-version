<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test\Comparator;

use PHPUnit\Framework\TestCase;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of version number less than version.
 */
final class CompareVersionLessThanTest extends TestCase
{
    /**
     * @covers \ptlis\SemanticVersion\Comparator\LessThan<extended>
     */
    public function testReadSymbol()
    {
        $lessThan = new LessThan();

        $this->assertEquals('<', $lessThan->getSymbol());
        $this->assertEquals('<', strval($lessThan));
    }

    /**
     * @dataProvider isLessProvider
     * @covers \ptlis\SemanticVersion\Comparator\LessThan<extended>
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsGreater($lVersion, $rVersion)
    {
        $lessThan = new LessThan();

        $this->assertTrue($lessThan->compare($lVersion, $rVersion));
    }

    /**
     * @dataProvider isNotLessProvider
     * @covers \ptlis\SemanticVersion\Comparator\LessThan<extended>
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsNotLess($lVersion, $rVersion)
    {
        $lessThan = new LessThan();

        $this->assertFalse($lessThan->compare($lVersion, $rVersion));
    }

    public function isLessProvider()
    {
        return [
            [
                new Version(1),
                new Version(2)
            ],
            [
                new Version(1, 0),
                new Version(1, 1)
            ],
            [
                new Version(1, 0, 0),
                new Version(1, 0, 1)
            ],
            [
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_DEV, null, 'flibble')),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_ALPHA))
            ],
            [
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_BETA))
            ],
            [
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_BETA)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC))
            ],
            [
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 2)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 4))
            ],
            [
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 4)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_ABSENT))
            ]
        ];
    }

    public function isNotLessProvider()
    {
        return [
            [
                new Version(2),
                new Version(1)
            ],
            [
                new Version(1),
                new Version(1)
            ],
            [
                new Version(1, 1),
                new Version(1, 0)
            ],
            [
                new Version(1, 1),
                new Version(1, 1)
            ],
            [
                new Version(1, 0, 1),
                new Version(1, 0, 0)
            ],
            [
                new Version(1, 0, 1),
                new Version(1, 0, 1)
            ],
            [
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_DEV, null, 'flibble'))
            ],
            [
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_BETA)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_ALPHA))
            ],
            [
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_BETA))
            ],
            [
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC))
            ],
            [
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 4)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 2))
            ],
            [
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 5)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 5))
            ],
            [
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_ABSENT)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 5))
            ]
        ];
    }
}
