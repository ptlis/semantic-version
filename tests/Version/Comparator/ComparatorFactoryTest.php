<?php

/**
 * Tests to ensure correct behaviour of ComparatorFactory.
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

namespace tests\Version\Comparator;

use ptlis\SemanticVersion\Version\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Version\Comparator\EqualTo;
use ptlis\SemanticVersion\Version\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Version\Comparator\GreaterThan;
use ptlis\SemanticVersion\Version\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Version\Comparator\LessThan;

/**
 * Tests to ensure correct behaviour of ComparatorFactory.
 */
class ComparatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testEqualTo()
    {
        $factory = new ComparatorFactory();

        $this->assertEquals(new EqualTo(), $factory->get('='));
    }


    public function testGreaterThan()
    {
        $factory = new ComparatorFactory();

        $this->assertEquals(new GreaterThan(), $factory->get('>'));
    }


    public function testGreaterOrEqualTo()
    {
        $factory = new ComparatorFactory();

        $this->assertEquals(new GreaterOrEqualTo(), $factory->get('>='));
    }


    public function testLessThan()
    {
        $factory = new ComparatorFactory();

        $this->assertEquals(new LessThan(), $factory->get('<'));
    }


    public function testLessOrEqualTo()
    {
        $factory = new ComparatorFactory();

        $this->assertEquals(new LessOrEqualTo(), $factory->get('<='));
    }


    public function testInvalidComparator()
    {
        $comparatorStr = '<<';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidComparatorException',
            'The provided comparator "' . $comparatorStr . '" is invalid.'
        );

        $factory = new ComparatorFactory();
        $factory->get($comparatorStr);
    }


    public function testCustomComparatorListValid()
    {
        $factory = new ComparatorFactory();
        $factory->setTypeList(array('=' => 'ptlis\SemanticVersion\Version\Comparator\EqualTo'));

        $this->assertEquals(new EqualTo(), $factory->get('='));
    }


    public function testCustomComparatorListInvalid()
    {
        $comparatorStr = '<';

        $this->setExpectedException(
            'ptlis\SemanticVersion\Exception\InvalidComparatorException',
            'The provided comparator "' . $comparatorStr . '" is invalid.'
        );

        $factory = new ComparatorFactory();
        $factory->setTypeList(array('=' => 'ptlis\SemanticVersion\Version\Comparator\EqualTo'));

        $factory->get($comparatorStr);
    }


    public function testRemoveComparator()
    {
        $comparatorStr = '=';

        $this->setExpectedException(
            'ptlis\SemanticVersion\Exception\InvalidComparatorException',
            'The provided comparator "' . $comparatorStr . '" is invalid.'
        );

        $factory = new ComparatorFactory();
        $factory->removeType($comparatorStr);

        $factory->get($comparatorStr);
    }


    public function testClassDoesntExist()
    {
        $className = 'ptlis\SemanticVersion\Version\Comparator\Boo';

        $this->setExpectedException(
            '\RuntimeException',
            'The class "' . $className . '" does not exist'
        );

        $factory = new ComparatorFactory();
        $factory->addType('boo', $className);
    }


    public function testClassDoesntImplementInterface()
    {
        $className = 'tests\Version\Comparator\InvalidComparator';

        $this->setExpectedException(
            '\RuntimeException',
            'Comparators must implement the ptlis\SemanticVersion\Version\Comparator\ComparatorInterface interface'
        );

        $factory = new ComparatorFactory();
        $factory->addType('invalid', $className);
    }
}
