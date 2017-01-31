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
use ptlis\SemanticVersion\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of version number less than version.
 */
final class CompareVersionLessOrEqualToTest extends TestCase
{
    /**
     * @covers \ptlis\SemanticVersion\Comparator\LessOrEqualTo<extended>
     */
    public function testReadSymbol()
    {
        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertEquals('<=', $lessOrEqualTo->getSymbol());
        $this->assertEquals('<=', strval($lessOrEqualTo));
    }

    /**
     * @dataProvider isLessOrEqualProvider
     * @covers \ptlis\SemanticVersion\Comparator\LessOrEqualTo<extended>
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsLessOrEqual($lVersion, $rVersion)
    {
        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($lVersion, $rVersion));
    }

    /**
     * @dataProvider isNotLessOrEqualProvider
     * @covers \ptlis\SemanticVersion\Comparator\LessOrEqualTo<extended>
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsNotLessOrEqual($lVersion, $rVersion)
    {
        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertFalse($lessOrEqualTo->compare($lVersion, $rVersion));
    }

    public function isLessOrEqualProvider()
    {
        return [
            [
                new Version(1),
                new Version(2)
            ],
            [
                new Version(1),
                new Version(1)
            ],
            [
                new Version(1, 3),
                new Version(1, 5)
            ],
            [
                new Version(1, 3),
                new Version(1, 3)
            ],
            [
                new Version(1, 5, 3),
                new Version(1, 5, 7)
            ],
            [
                new Version(1, 5, 7),
                new Version(1, 5, 7)
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_DEV, null, 'foo')),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_BETA))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_BETA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 1)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 5)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 5))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
            ]
        ];
    }

    public function isNotLessOrEqualProvider()
    {
        return [
            [
                new Version(2),
                new Version(1)
            ],
            [
                new Version(1, 5),
                new Version(1, 3)
            ],
            [
                new Version(1, 5, 7),
                new Version(1, 5, 3)
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_DEV, null, 'foo'))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_BETA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_BETA))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 1))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 1))
            ]
        ];
    }
}
