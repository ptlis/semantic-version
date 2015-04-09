<?php

/**
 * PHP Version 5.3
 *
 * @copyright   (c) 2014-2015 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test\Version\Comparator;

use ptlis\SemanticVersion\Version\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of version number less than version.
 */
class CompareVersionLessOrEqualToTest extends \PHPUnit_Framework_TestCase
{
    public function testReadSymbol()
    {
        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertEquals(
            '<=',
            $lessOrEqualTo->getSymbol()
        );

        $this->assertEquals(
            '<=',
            strval($lessOrEqualTo)
        );
    }

    /**
     * @dataProvider isLessOrEqualProvider
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsLessOrEqual($lVersion, $rVersion)
    {
        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue(
            $lessOrEqualTo->compare($lVersion, $rVersion)
        );
    }

    /**
     * @dataProvider isNotLessOrEqualProvider
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsNotLessOrEqual($lVersion, $rVersion)
    {
        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertFalse(
            $lessOrEqualTo->compare($lVersion, $rVersion)
        );
    }

    public function isLessOrEqualProvider()
    {
        return array(
            array(
                new Version(1),
                new Version(2)
            ),
            array(
                new Version(1),
                new Version(1)
            ),
            array(
                new Version(1, 3),
                new Version(1, 5)
            ),
            array(
                new Version(1, 3),
                new Version(1, 3)
            ),
            array(
                new Version(1, 5, 3),
                new Version(1, 5, 7)
            ),
            array(
                new Version(1, 5, 7),
                new Version(1, 5, 7)
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_DEV, null, 'foo')),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_BETA))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_BETA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 1)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 5)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 5))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
            ),
        );
    }

    public function isNotLessOrEqualProvider()
    {
        return array(
            array(
                new Version(2),
                new Version(1)
            ),
            array(
                new Version(1, 5),
                new Version(1, 3)
            ),
            array(
                new Version(1, 5, 7),
                new Version(1, 5, 3)
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_DEV, null, 'foo'))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_BETA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_BETA))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 1))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 1))
            )
        );
    }
}
