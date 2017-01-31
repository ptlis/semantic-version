<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test\Version\Label;

use ptlis\SemanticVersion\Version\Label\Label;

class SpecificLabelTest extends \PHPUnit_Framework_TestCase
{
    public function testAbsent()
    {
        $label = new Label(Label::PRECEDENCE_ABSENT);

        $this->assertEquals(
            strval($label),
            ''
        );

        $this->assertEquals(
            Label::PRECEDENCE_ABSENT,
            $label->getPrecedence()
        );
    }

    public function testDev()
    {
        $label = new Label(Label::PRECEDENCE_DEV, null, 'bob');

        $this->assertEquals(
            strval($label),
            'bob'
        );

        $this->assertEquals(
            Label::PRECEDENCE_DEV,
            $label->getPrecedence()
        );
    }

    public function testDevWithVersion()
    {
        $label = new Label(Label::PRECEDENCE_DEV, 3, 'bob');

        $this->assertEquals(
            strval($label),
            'bob.3'
        );
    }

    public function testAlpha()
    {
        $label = new Label(Label::PRECEDENCE_ALPHA);

        $this->assertEquals(
            strval($label),
            'alpha'
        );

        $this->assertEquals(
            Label::PRECEDENCE_ALPHA,
            $label->getPrecedence()
        );
    }

    public function testAlphaWithVersion()
    {
        $label = new Label(Label::PRECEDENCE_ALPHA, 5);

        $this->assertEquals(
            strval($label),
            'alpha.5'
        );
    }

    public function testBeta()
    {
        $label = new Label(Label::PRECEDENCE_BETA);

        $this->assertEquals(
            strval($label),
            'beta'
        );

        $this->assertEquals(
            Label::PRECEDENCE_BETA,
            $label->getPrecedence()
        );
    }

    public function testBetaWithVersion()
    {
        $label = new Label(Label::PRECEDENCE_BETA, 2);

        $this->assertEquals(
            strval($label),
            'beta.2'
        );
    }

    public function testRc()
    {
        $label = new Label(Label::PRECEDENCE_RC);

        $this->assertEquals(
            strval($label),
            'rc'
        );

        $this->assertEquals(
            Label::PRECEDENCE_RC,
            $label->getPrecedence()
        );
    }

    public function testRcWithVersion()
    {
        $label = new Label(Label::PRECEDENCE_RC, 2);

        $this->assertEquals(
            strval($label),
            'rc.2'
        );
    }
}
