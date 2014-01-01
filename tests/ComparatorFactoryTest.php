<?php

/**
 * Tests to ensure correct behaviour of ComparatorFactory.
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

use ptlis\SemanticVersion\Entity\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Entity\Comparator\EqualTo;
use ptlis\SemanticVersion\Entity\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Entity\Comparator\GreaterThan;
use ptlis\SemanticVersion\Entity\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Entity\Comparator\LessThan;

/**
 * Tests to ensure correct behaviour of ComparatorFactory.
 */
class ComparatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testEqualTo()
    {
        $factory = new ComparatorFactory();

        $this->assertEquals(new EqualTo(), $factory->getComparator('='));
    }


    public function testGreaterThan()
    {
        $factory = new ComparatorFactory();

        $this->assertEquals(new GreaterThan(), $factory->getComparator('>'));
    }


    public function testGreaterOrEqualTo()
    {
        $factory = new ComparatorFactory();

        $this->assertEquals(new GreaterOrEqualTo(), $factory->getComparator('>='));
    }


    public function testLessThan()
    {
        $factory = new ComparatorFactory();

        $this->assertEquals(new LessThan(), $factory->getComparator('<'));
    }


    public function testLessOrEqualTo()
    {
        $factory = new ComparatorFactory();

        $this->assertEquals(new LessOrEqualTo(), $factory->getComparator('<='));
    }


    public function testInvalidComparator()
    {
        $comparatorStr = '<<';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidComparatorException',
            'The provided comparator "' . $comparatorStr . '" is invalid.'
        );

        $factory = new ComparatorFactory();
        $factory->getComparator($comparatorStr);
    }


    public function testCustomComparatorListValid()
    {
        $factory = new ComparatorFactory(
            [
                '=' => 'ptlis\SemanticVersion\Entity\Comparator\EqualTo'
            ]
        );

        $this->assertEquals(new EqualTo(), $factory->getComparator('='));
    }


    public function testCustomComparatorListInalid()
    {
        $comparatorStr = '<';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidComparatorException',
            'The provided comparator "' . $comparatorStr . '" is invalid.'
        );

        $factory = new ComparatorFactory(
            [
                '=' => 'ptlis\SemanticVersion\Entity\Comparator\EqualTo'
            ]
        );

        $factory->getComparator($comparatorStr);
    }
}
