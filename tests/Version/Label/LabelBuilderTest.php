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
use ptlis\SemanticVersion\Version\Label\LabelBuilder;

class LabelBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDev()
    {
        $label = (new LabelBuilder())
            ->setName('wibble')
            ->setVersion(5)
            ->build();

        $this->assertEquals(new Label(Label::PRECEDENCE_DEV, 5, 'wibble'), $label);
    }

    public function testCreateAlpha()
    {
        $label = (new LabelBuilder())
            ->setName('alpha')
            ->setVersion(1)
            ->build();

        $this->assertEquals(new Label(Label::PRECEDENCE_ALPHA, 1), $label);
    }

    public function testCreateBeta()
    {
        $label = (new LabelBuilder())
            ->setName('beta')
            ->build();

        $this->assertEquals(new Label(Label::PRECEDENCE_BETA), $label);
    }

    public function testCreateRC()
    {
        $label = (new LabelBuilder())
            ->setName('rc')
            ->setVersion(3)
            ->build();

        $this->assertEquals(new Label(Label::PRECEDENCE_RC, 3), $label);
    }

    public function testCreateAbsent()
    {
        $label = (new LabelBuilder())->build();

        $this->assertEquals(new Label(Label::PRECEDENCE_ABSENT), $label);
    }
}
