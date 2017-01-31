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

class LabelTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $label = new Label(Label::PRECEDENCE_RC, '1');

        $this->assertEquals(Label::PRECEDENCE_RC, $label->getPrecedence());
        $this->assertEquals('rc', $label->getName());
        $this->assertEquals('1', $label->getVersion());
        $this->assertEquals('rc.1', strval($label));
    }

    public function testCreateAbsent()
    {
        $label = new Label(Label::PRECEDENCE_ABSENT);

        $this->assertEquals('', strval($label));
    }

    // TODO: Rework once build metadata is re-added
//    public function testCreateWithBuildMetadata()
//    {
//        $label = new Label(
//            Label::PRECEDENCE_ALPHA,
//            'alpha',
//            '1',
//            '2015-04-01'
//        );
//
//        $this->assertEquals(
//            'alpha.1+2015-04-01',
//            strval($label)
//        );
//    }
}
