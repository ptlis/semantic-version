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

use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;

class LabelBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDev()
    {
        $builder = new LabelBuilder();

        $label = $builder
            ->setName('wibble')
            ->setVersion(5)
            ->build();

        $this->assertEquals(
            new Label(Label::PRECEDENCE_DEV, 5, 'wibble'),
            $label
        );
    }

    public function testCreateAlpha()
    {
        $builder = new LabelBuilder();

        $label = $builder
            ->setName('alpha')
            ->setVersion(1)
            ->build();

        $this->assertEquals(
            new Label(Label::PRECEDENCE_ALPHA, 1),
            $label
        );
    }

    public function testCreateBeta()
    {
        $builder = new LabelBuilder();

        $label = $builder
            ->setName('beta')
            ->build();

        $this->assertEquals(
            new Label(Label::PRECEDENCE_BETA),
            $label
        );
    }

    public function testCreateRC()
    {
        $builder = new LabelBuilder();

        $label = $builder
            ->setName('rc')
            ->setVersion(3)
            ->build();

        $this->assertEquals(
            new Label(Label::PRECEDENCE_RC, 3),
            $label
        );
    }

    public function testCreateAbsent()
    {
        $builder = new LabelBuilder();

        $label = $builder->build();

        $this->assertEquals(
            new Label(Label::PRECEDENCE_ABSENT),
            $label
        );
    }
}
