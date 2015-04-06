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

namespace ptlis\SemanticVersion\Test\Version\Label;

use ptlis\SemanticVersion\Version\Label\LabelAbsent;
use ptlis\SemanticVersion\Version\Label\LabelAlpha;
use ptlis\SemanticVersion\Version\Label\LabelBeta;
use ptlis\SemanticVersion\Version\Label\LabelDev;
use ptlis\SemanticVersion\Version\Label\LabelInterface;
use ptlis\SemanticVersion\Version\Label\LabelRc;

class SpecificLabelTest extends \PHPUnit_Framework_TestCase
{
    public function testAbsent()
    {
        $label = new LabelAbsent();

        $this->assertEquals(
            strval($label),
            ''
        );

        $this->assertEquals(
            LabelInterface::PRECEDENCE_ABSENT,
            $label->getPrecedence()
        );
    }

    public function testDev()
    {
        $label = new LabelDev('bob');

        $this->assertEquals(
            strval($label),
            'bob'
        );

        $this->assertEquals(
            LabelInterface::PRECEDENCE_DEV,
            $label->getPrecedence()
        );
    }

    public function testDevWithVersion()
    {
        $label = new LabelDev('bob', 3);

        $this->assertEquals(
            strval($label),
            'bob.3'
        );
    }

    public function testAlpha()
    {
        $label = new LabelAlpha();

        $this->assertEquals(
            strval($label),
            'alpha'
        );

        $this->assertEquals(
            LabelInterface::PRECEDENCE_ALPHA,
            $label->getPrecedence()
        );
    }

    public function testAlphaWithVersion()
    {
        $label = new LabelAlpha(5);

        $this->assertEquals(
            strval($label),
            'alpha.5'
        );
    }

    public function testBeta()
    {
        $label = new LabelBeta();

        $this->assertEquals(
            strval($label),
            'beta'
        );

        $this->assertEquals(
            LabelInterface::PRECEDENCE_BETA,
            $label->getPrecedence()
        );
    }

    public function testBetaWithVersion()
    {
        $label = new LabelBeta(2);

        $this->assertEquals(
            strval($label),
            'beta.2'
        );
    }

    public function testRc()
    {
        $label = new LabelRc();

        $this->assertEquals(
            strval($label),
            'rc'
        );

        $this->assertEquals(
            LabelInterface::PRECEDENCE_RC,
            $label->getPrecedence()
        );
    }

    public function testRcWithVersion()
    {
        $label = new LabelRc(2);

        $this->assertEquals(
            strval($label),
            'rc.2'
        );
    }
}
