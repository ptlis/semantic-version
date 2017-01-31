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
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of version number greater than version.
 */
final class GreaterOrEqualToTest extends TestCase
{
    /**
     * @covers \ptlis\SemanticVersion\Comparator\GreaterOrEqualTo<extended>
     */
    public function testReadSymbol()
    {
        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertEquals('>=', $greaterOrEqualTo->getSymbol());
        $this->assertEquals('>=', strval($greaterOrEqualTo));
    }

    /**
     * @dataProvider isGreaterOrEqualProvider
     * @covers \ptlis\SemanticVersion\Comparator\GreaterOrEqualTo<extended>
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsGreaterOrEqual($lVersion, $rVersion)
    {
        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($lVersion, $rVersion));
    }

    /**
     * @dataProvider isNotGreaterOrEqualProvider
     * @covers \ptlis\SemanticVersion\Comparator\GreaterOrEqualTo<extended>
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsNotGreaterOrEqual($lVersion, $rVersion)
    {
        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertFalse($greaterOrEqualTo->compare($lVersion, $rVersion));
    }

    public function isGreaterOrEqualProvider()
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
                new Version(1, 5),
                new Version(1, 3)
            ],
            [
                new Version(1, 3),
                new Version(1, 3)
            ],
            [
                new Version(1, 5, 7),
                new Version(1, 5, 3)
            ],
            [
                new Version(1, 5, 7),
                new Version(1, 5, 7)
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_DEV, null, 'test'))
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
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2))
            ]
        ];
    }

    public function isNotGreaterOrEqualProvider()
    {
        return [
            [
                new Version(1),
                new Version(2)
            ],
            [
                new Version(1, 3),
                new Version(1, 5)
            ],
            [
                new Version(1, 5, 3),
                new Version(1, 5, 7)
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_DEV, null, 'test')),
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
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
            ]
        ];
    }
}
