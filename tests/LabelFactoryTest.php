<?php

/**
 * Tests to ensure correct behaviour of LabelFactory.
 *
 * PHP Version 5.4
 *
 * Based off the tests for vierbergenlars\SemVar https://github.com/vierbergenlars/php-semver/
 *
 * @copyright   (c) 2014 Brian Ridley
 * @author      Brian Ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests;

use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Label\LabelFactory;
use ptlis\SemanticVersion\Label\LabelNone;
use ptlis\SemanticVersion\Label\LabelRc;

/**
 * Tests to ensure correct behaviour of ComparatorFactory.
 */
class LabelFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testLabelAlpha()
    {
        $factory = new LabelFactory();

        $this->assertEquals(new LabelAlpha(), $factory->get('alpha'));
    }


    public function testLabelBeta()
    {
        $factory = new LabelFactory();

        $this->assertEquals(new LabelBeta(), $factory->get('beta'));
    }


    public function testLabelRc()
    {
        $factory = new LabelFactory();

        $this->assertEquals(new LabelRc(), $factory->get('rc'));
    }


    public function testLabelNone()
    {
        $factory = new LabelFactory();

        $this->assertEquals(new LabelNone(), $factory->get(''));
    }


    public function testInvalidLabel()
    {
        $factory = new LabelFactory();

        $this->assertEquals(new LabelNone(), $factory->get('foobar'));
    }


    public function testCustomLabelListValid()
    {
        $factory = new LabelFactory(
            [
                '=' => 'ptlis\SemanticVersion\Label\LabelAlpha'
            ]
        );

        $this->assertEquals(new LabelNone(), $factory->get('beta'));
    }
}
