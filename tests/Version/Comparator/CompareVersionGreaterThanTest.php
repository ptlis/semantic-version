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

use ptlis\SemanticVersion\Version\Comparator\GreaterThan;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of version number greater than version.
 */
class CompareVersionGreaterThanTest extends \PHPUnit_Framework_TestCase
{
    public function testReadSymbol()
    {
        $greaterThan = new GreaterThan();

        $this->assertEquals(
            '>',
            $greaterThan->getSymbol()
        );

        $this->assertEquals(
            '>',
            strval($greaterThan)
        );
    }

    /**
     * @dataProvider isGreaterProvider
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsGreater($lVersion, $rVersion)
    {
        $greaterThan = new GreaterThan();

        $this->assertTrue(
            $greaterThan->compare($lVersion, $rVersion)
        );
    }

    /**
     * @dataProvider isNotGreaterProvider
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsNotGreater($lVersion, $rVersion)
    {
        $greaterThan = new GreaterThan();

        $this->assertFalse(
            $greaterThan->compare($lVersion, $rVersion)
        );
    }

    public function isGreaterProvider()
    {
        return array(
            array(
                new Version(2),
                new Version(1)
            ),
            array(
                new Version(1, 1),
                new Version(1, 0)
            ),
            array(
                new Version(1, 0, 1),
                new Version(1, 0, 0)
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ALPHA)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_DEV, null, 'test'))
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
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC))
            )
        );
    }

    public function isNotGreaterProvider()
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
                new Version(1, 0),
                new Version(1, 1)
            ),
            array(
                new Version(1, 0),
                new Version(1, 0)
            ),
            array(
                new Version(1, 0, 0),
                new Version(1, 0, 1)
            ),
            array(
                new Version(1, 0, 1),
                new Version(1, 0, 1)
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_DEV, null, 'test')),
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
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2))
            ),
            array(
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2)),
                new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
            )
        );
    }
}
