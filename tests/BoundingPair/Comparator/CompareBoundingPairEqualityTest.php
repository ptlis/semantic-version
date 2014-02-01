<?php

/**
 * Tests to ensure correct handling of BoundingPair equality comparator.
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

namespace tests\BoundingPair\Comparator;

use ptlis\SemanticVersion\BoundingPair\Comparator\EqualTo as BoundingPairEqualTo;
use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct handling of BoundingPair equality comparator.
 */
class CompareBoundingPairEqualityTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSymbol()
    {
        $equalTo = new BoundingPairEqualTo();
        $this->assertSame('=', $equalTo->getSymbol());
    }


    public function testToString()
    {
        $equalTo = new BoundingPairEqualTo();
        $this->assertSame('=', $equalTo->__toString());
    }


    public function testEqual()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>1.0.0<=2.0.0');
        $boundingPair2 = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $equalTo = new BoundingPairEqualTo();

        $this->assertTrue($equalTo->compare($boundingPair1, $boundingPair2));
    }


    public function testEqualLowerNull()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('<=2.0.0');
        $boundingPair2 = $engine->parseBoundingPair('<=2.0.0');

        $equalTo = new BoundingPairEqualTo();

        $this->assertTrue($equalTo->compare($boundingPair1, $boundingPair2));
    }


    public function testEqualUpperNull()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>1.0.0');
        $boundingPair2 = $engine->parseBoundingPair('>1.0.0');

        $equalTo = new BoundingPairEqualTo();

        $this->assertTrue($equalTo->compare($boundingPair1, $boundingPair2));
    }


    public function testNotEqual()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>1.0.0<=2.0.0');
        $boundingPair2 = $engine->parseBoundingPair('>1.0.0<=3.0.0');

        $equalTo = new BoundingPairEqualTo();

        $this->assertFalse($equalTo->compare($boundingPair1, $boundingPair2));
    }


    public function testNotEqualLowerNull()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('<=2.0.0');
        $boundingPair2 = $engine->parseBoundingPair('<=3.0.0');

        $equalTo = new BoundingPairEqualTo();

        $this->assertFalse($equalTo->compare($boundingPair1, $boundingPair2));
    }


    public function testNotEqualUpperNull()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>2.0.0');
        $boundingPair2 = $engine->parseBoundingPair('>1.0.0');

        $equalTo = new BoundingPairEqualTo();

        $this->assertFalse($equalTo->compare($boundingPair1, $boundingPair2));
    }
}
