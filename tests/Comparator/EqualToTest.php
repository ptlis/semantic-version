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
use ptlis\SemanticVersion\Comparator\EqualTo;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of version number equality.
 */
final class EqualToTest extends TestCase
{
    /**
     * @covers \ptlis\SemanticVersion\Comparator\EqualTo<extended>
     */
    public function testReadSymbol()
    {
        $equalTo = new EqualTo();

        $this->assertEquals('=', $equalTo->getSymbol());
        $this->assertEquals('=', strval($equalTo));
    }

    /**
     * @dataProvider isEqualProvider
     * @covers \ptlis\SemanticVersion\Comparator\EqualTo<extended>
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsEqual($lVersion, $rVersion)
    {
        $equalTo = new EqualTo();

        $this->assertTrue($equalTo->compare($lVersion, $rVersion));
    }

    /**
     * @dataProvider isNotEqualProvider
     * @covers \ptlis\SemanticVersion\Comparator\EqualTo<extended>
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsNotEqual($lVersion, $rVersion)
    {
        $equalTo = new EqualTo();

        $this->assertFalse($equalTo->compare($lVersion, $rVersion));
    }

    public function isEqualProvider()
    {
        return [
            [
                new Version(1, 0, 0),
                new Version(1, 0, 0)
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA, 1)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA, 1))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA, 1)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA, 1))
            ]
        ];
    }

    public function isNotEqualProvider()
    {
        return [
            [
                new Version(1, 0, 0),
                new Version(2, 0, 0)
            ],
            [
                new Version(1, 0, 0),
                new Version(1, 1, 0)
            ],
            [
                new Version(1, 0, 0),
                new Version(1, 0, 5)
            ],
            [
                new Version(1, 0, 0),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA, 1))
            ],
            [
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_BETA))
            ]
        ];
    }
}
