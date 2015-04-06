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

namespace ptlis\SemanticVersion\Test\Comparator;

use ptlis\SemanticVersion\Version\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Version\Label\LabelAbsent;
use ptlis\SemanticVersion\Version\Label\LabelAlpha;
use ptlis\SemanticVersion\Version\Label\LabelBeta;
use ptlis\SemanticVersion\Version\Label\LabelDev;
use ptlis\SemanticVersion\Version\Label\LabelRc;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of version number greater than version.
 */
class CompareVersionGreaterOrEqualToTest extends \PHPUnit_Framework_TestCase
{
    public function testReadSymbol()
    {
        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertEquals(
            '>=',
            $greaterOrEqualTo->getSymbol()
        );

        $this->assertEquals(
            '>=',
            strval($greaterOrEqualTo)
        );
    }

    /**
     * @dataProvider isGreaterOrEqualProvider
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsGreaterOrEqual($lVersion, $rVersion)
    {
        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue(
            $greaterOrEqualTo->compare($lVersion, $rVersion)
        );
    }

    /**
     * @dataProvider isNotGreaterOrEqualProvider
     *
     * @param Version $lVersion
     * @param Version $rVersion
     */
    public function testIsNotGreaterOrEqual($lVersion, $rVersion)
    {
        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertFalse(
            $greaterOrEqualTo->compare($lVersion, $rVersion)
        );
    }

    public function isGreaterOrEqualProvider()
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
                new Version(1, 5),
                new Version(1, 3)
            ),
            array(
                new Version(1, 3),
                new Version(1, 3)
            ),
            array(
                new Version(1, 5, 7),
                new Version(1, 5, 3)
            ),
            array(
                new Version(1, 5, 7),
                new Version(1, 5, 7)
            ),
            array(
                new Version(1, 0, 0, new LabelDev('test')),
                new Version(1, 0, 0, new LabelAbsent())
            ),
            array(
                new Version(1, 0, 0, new LabelAlpha()),
                new Version(1, 0, 0, new LabelDev('test'))
            ),
            array(
                new Version(1, 0, 0, new LabelBeta()),
                new Version(1, 0, 0, new LabelAlpha())
            ),
            array(
                new Version(1, 0, 0, new LabelRc()),
                new Version(1, 0, 0, new LabelBeta())
            ),
            array(
                new Version(1, 0, 0, new LabelAbsent()),
                new Version(1, 0, 0, new LabelAbsent())
            ),
            array(
                new Version(1, 0, 0, new LabelRc(2)),
                new Version(1, 0, 0, new LabelRc())
            ),
            array(
                new Version(1, 0, 0, new LabelRc(2)),
                new Version(1, 0, 0, new LabelRc(2))
            )
        );
    }

    public function isNotGreaterOrEqualProvider()
    {
        return array(
            array(
                new Version(1),
                new Version(2)
            ),
            array(
                new Version(1, 3),
                new Version(1, 5)
            ),
            array(
                new Version(1, 5, 3),
                new Version(1, 5, 7)
            ),
            array(
                new Version(1, 0, 0, new LabelAbsent()),
                new Version(1, 0, 0, new LabelDev('test'))
            ),
            array(
                new Version(1, 0, 0, new LabelDev('test')),
                new Version(1, 0, 0, new LabelAlpha())
            ),
            array(
                new Version(1, 0, 0, new LabelAlpha()),
                new Version(1, 0, 0, new LabelBeta())
            ),
            array(
                new Version(1, 0, 0, new LabelBeta()),
                new Version(1, 0, 0, new LabelRc())
            ),
            array(
                new Version(1, 0, 0, new LabelRc()),
                new Version(1, 0, 0, new LabelRc(2))
            )
        );
    }
}
