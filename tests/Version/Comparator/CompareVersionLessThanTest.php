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

use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of version number less than version.
 */
class CompareVersionLessThanTest extends \PHPUnit_Framework_TestCase
{
    public function testReadSymbol()
    {
        $lessThan = new LessThan();

        $this->assertEquals(
            '<',
            $lessThan->getSymbol()
        );

        $this->assertEquals(
            '<',
            strval($lessThan)
        );
    }

    /**
     * @dataProvider isLessProvider
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsGreater($lVersion, $rVersion)
    {
        $lessThan = new LessThan();

        $this->assertTrue(
            $lessThan->compare($lVersion, $rVersion)
        );
    }

    /**
     * @dataProvider isNotLessProvider
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsNotLess($lVersion, $rVersion)
    {
        $lessThan = new LessThan();

        $this->assertFalse(
            $lessThan->compare($lVersion, $rVersion)
        );
    }

    public function isLessProvider()
    {
        return array(
            array(
                new Version(1),
                new Version(2)
            ),
            array(
                new Version(1, 0),
                new Version(1, 1)
            ),
            array(
                new Version(1, 0, 0),
                new Version(1, 0, 1)
            ),
            array(
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_DEV, null, 'flibble')),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_ALPHA))
            ),
            array(
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_BETA))
            ),
            array(
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_BETA)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC))
            ),
            array(
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 2)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 4))
            ),
            array(
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 4)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_ABSENT))
            )
        );
    }

    public function isNotLessProvider()
    {
        return array(
            array(
                new Version(2),
                new Version(1)
            ),
            array(
                new Version(1),
                new Version(1)
            ),
            array(
                new Version(1, 1),
                new Version(1, 0)
            ),
            array(
                new Version(1, 1),
                new Version(1, 1)
            ),
            array(
                new Version(1, 0, 1),
                new Version(1, 0, 0)
            ),
            array(
                new Version(1, 0, 1),
                new Version(1, 0, 1)
            ),
            array(
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_DEV, null, 'flibble'))
            ),
            array(
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_BETA)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_ALPHA))
            ),
            array(
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_BETA))
            ),
            array(
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC))
            ),
            array(
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 4)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 2))
            ),
            array(
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 5)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 5))
            ),
            array(
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_ABSENT)),
                new Version(1, 0, 1, new Label(Label::PRECEDENCE_RC, 5))
            )
        );
    }
}
