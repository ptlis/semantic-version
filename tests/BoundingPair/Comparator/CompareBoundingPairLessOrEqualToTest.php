<?php

/**
 * Tests to ensure correct handling of BoundingPair LessOrEqualTo comparator.
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

namespace ptlis\SemanticVersion\Test\BoundingPair\Comparator;

use ptlis\SemanticVersion\BoundingPair\Comparator\LessOrEqualTo as BoundingLessOrEqualTo;
use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct handling of BoundingPair LessThan comparator.
 */
class CompareBoundingPairLessOrEqualToTest extends \PHPUnit_Framework_TestCase
{

    public function testGetSymbol()
    {
        $lessOrEqualTo = new BoundingLessOrEqualTo();
        $this->assertSame('<=', $lessOrEqualTo->getSymbol());
    }


    public function testToString()
    {
        $lessOrEqualTo = new BoundingLessOrEqualTo();
        $this->assertSame('<=', $lessOrEqualTo->__toString());
    }


    public function testEqual()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>1.0.0<=2.0.0');
        $boundingPair2 = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $lessOrEqualTo = new BoundingLessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($boundingPair1, $boundingPair2));
    }


    public function testLessThanUpperNull()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>1.0.0<=2.0.0');
        $boundingPair2 = $engine->parseBoundingPair('>1.0.0');

        $lessOrEqualTo = new BoundingLessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($boundingPair1, $boundingPair2));
    }


    public function testGreaterThanUpperNull()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>1.0.0');
        $boundingPair2 = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $lessOrEqualTo = new BoundingLessOrEqualTo();

        $this->assertFalse($lessOrEqualTo->compare($boundingPair1, $boundingPair2));
    }


    public function testLessThanLowerNull()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('<=2.0.0');
        $boundingPair2 = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $lessOrEqualTo = new BoundingLessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($boundingPair1, $boundingPair2));
    }


    public function testGreaterThanLowerNull()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>1.0.0<=2.0.0');
        $boundingPair2 = $engine->parseBoundingPair('<=2.0.0');

        $lessOrEqualTo = new BoundingLessOrEqualTo();

        $this->assertFalse($lessOrEqualTo->compare($boundingPair1, $boundingPair2));
    }


    public function testLessThanUpper()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>1.0.0<=2.0.0');
        $boundingPair2 = $engine->parseBoundingPair('>1.0.0<=2.5.0');

        $lessOrEqualTo = new BoundingLessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($boundingPair1, $boundingPair2));
    }


    public function testGreaterThanUpper()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>1.0.0<=2.5.0');
        $boundingPair2 = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $lessOrEqualTo = new BoundingLessOrEqualTo();

        $this->assertFalse($lessOrEqualTo->compare($boundingPair1, $boundingPair2));
    }


    public function testLessThanLower()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>1.0.0<=2.0.0');
        $boundingPair2 = $engine->parseBoundingPair('>1.1.0<=2.0.0');

        $lessOrEqualTo = new BoundingLessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($boundingPair1, $boundingPair2));
    }


    public function testGreaterThanLower()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>1.1.0<=2.0.0');
        $boundingPair2 = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $lessOrEqualTo = new BoundingLessOrEqualTo();

        $this->assertFalse($lessOrEqualTo->compare($boundingPair1, $boundingPair2));
    }
}
