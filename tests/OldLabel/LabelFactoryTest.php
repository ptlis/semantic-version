<?php

/**
 * Tests to ensure correct behaviour of LabelFactory.
 *
 * PHP Version 5.3
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

namespace ptlis\SemanticVersion\Test\OldLabel;

use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Label\LabelDev;
use ptlis\SemanticVersion\Label\LabelFactory;
use ptlis\SemanticVersion\Label\LabelAbsent;
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


    public function testLabelAbsent()
    {
        $factory = new LabelFactory();

        $this->assertEquals(new LabelAbsent(), $factory->get(''));
    }


    public function testCustomLabelListValid()
    {
        $factory = new LabelFactory();
        $factory->setTypeList(array('=' => 'ptlis\SemanticVersion\Label\LabelAlpha'));

        $expectLabel = new LabelDev();
        $expectLabel->setName('beta');

        $this->assertEquals($expectLabel, $factory->get('beta'));
    }


    public function testRemoveLabel()
    {
        $factory = new LabelFactory();
        $factory->removeType('alpha');

        $expectLabel = new LabelDev();
        $expectLabel->setName('alpha');

        $this->assertEquals($expectLabel, $factory->get('alpha'));
    }


    public function testWildcardLabelClass()
    {
        $factory = new LabelFactory();
        $factory->setWildcardLabel('ptlis\SemanticVersion\Test\OldLabel\ReplacementWildcardLabel');

        $expectLabel = new ReplacementWildcardLabel();
        $expectLabel->setName('foobar');

        $this->assertEquals($expectLabel, $factory->get('foobar'));
    }


    public function testWildcardLabelClassDoesntExist()
    {
        $className = 'ptlis\SemanticVersion\Test\OldLabel\WildcardBoo';

        $this->setExpectedException(
            '\RuntimeException',
            'The class "' . $className . '" does not exist'
        );

        $factory = new LabelFactory();
        $factory->setWildcardLabel($className);
    }


    public function testWildcardLabelClassDoesntImplementInterface()
    {
        $className = 'ptlis\SemanticVersion\Test\OldLabel\InvalidReplacementWildcardLabel';

        $this->setExpectedException(
            '\RuntimeException',
            'Wildcard labels must implement the ptlis\SemanticVersion\Label\LabelWildcardInterface interface'
        );

        $factory = new LabelFactory();
        $factory->setWildcardLabel($className);
    }


    public function testAbsentLabelClass()
    {
        $factory = new LabelFactory();
        $factory->setAbsentLabel('ptlis\SemanticVersion\Test\OldLabel\ReplacementAbsentLabel');

        $expectLabel = new ReplacementAbsentLabel();

        $this->assertEquals($expectLabel, $factory->get(''));
    }


    public function testAbsentLabelClassDoesntExist()
    {
        $className = 'ptlis\SemanticVersion\Test\OldLabel\AbsentBoo';

        $this->setExpectedException(
            '\RuntimeException',
            'The class "' . $className . '" does not exist'
        );

        $factory = new LabelFactory();
        $factory->setAbsentLabel($className);
    }


    public function testAbsentLabelClassDoesntImplementInterface()
    {
        $className = 'ptlis\SemanticVersion\Test\OldLabel\InvalidReplacementAbsentLabel';

        $this->setExpectedException(
            '\RuntimeException',
            'Absent labels must implement the ptlis\SemanticVersion\Label\LabelAbsentInterface interface'
        );

        $factory = new LabelFactory();
        $factory->setAbsentLabel($className);
    }


    public function testClassDoesntExist()
    {
        $className = 'ptlis\SemanticVersion\Label\Boo';

        $this->setExpectedException(
            '\RuntimeException',
            'The class "' . $className . '" does not exist'
        );

        $factory = new LabelFactory();
        $factory->addType('boo', $className);
    }


    public function testClassDoesntImplementInterface()
    {
        $className = 'ptlis\SemanticVersion\Test\OldLabel\InvalidLabel';

        $this->setExpectedException(
            '\RuntimeException',
            'Labels must implement the ptlis\SemanticVersion\Label\LabelInterface interface'
        );

        $factory = new LabelFactory();
        $factory->addType('invalid', $className);
    }
}