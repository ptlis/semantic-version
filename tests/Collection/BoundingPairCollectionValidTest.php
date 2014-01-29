<?php

/**
 * Tests to validate correct use of BoundingPairCollection.
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

namespace tests\Collection;

use ptlis\SemanticVersion\Collection\BoundingPairCollection;
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Label\LabelAbsent;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to validate correct use of BoundingPairCollection.
 */
class BoundingPairCollectionValidTest extends \PHPUnit_Framework_TestCase
{
    public function testAddSingle()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>=1.0.0<2.0.0');

        $collection = new BoundingPairCollection();

        $collection[] = $boundingPair1;

        $this->assertSame('>=1.0.0<2.0.0', $collection->__toString());
    }


    public function testAddMulti()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>=1.0.0<2.0.0');
        $boundingPair2 = $engine->parseBoundingPair('>=2.0.0<2.5.0');

        $collection = new BoundingPairCollection();

        $collection[] = $boundingPair1;
        $collection[] = $boundingPair2;

        $this->assertSame('>=1.0.0<2.0.0, >=2.0.0<2.5.0', $collection->__toString());
    }


    public function testAddBulk()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=2.0.0<2.5.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $this->assertSame('>=1.0.0<2.0.0', $collection[0]->__toString());
        $this->assertSame('>=2.0.0<2.5.0', $collection[1]->__toString());
    }


    public function testCount()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=2.0.0<2.5.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $this->assertSame(2, count($collection));
    }


    public function testRemove()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=2.0.0<2.5.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        unset($collection[0]);

        $this->assertSame(1, count($collection));
        $this->assertSame('>=2.0.0<2.5.0', $collection[1]->__toString());
    }


    public function testRemoveIndexDoesNotExist()
    {
        $engine = new VersionEngine();

        $boundingPairList = $engine->parseBoundingPair('>=1.0.0<2.0.0');

        $collection = new BoundingPairCollection();

        $collection[] = $boundingPairList;

        unset($collection[1]);

        $this->assertSame(1, count($collection));
        $this->assertSame('>=1.0.0<2.0.0', $collection[0]->__toString());
    }


    public function testToString()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=2.0.0<=2.5.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=2.7.0<4.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $this->assertSame(
            '>=1.0.0<2.0.0, >=2.0.0<=2.5.0, >=2.7.0<4.0.0',
            $collection->__toString()
        );
    }


    public function testIterator()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=2.0.0<=2.5.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=2.7.0<4.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        foreach ($collection as $boundingPair) {
            $this->assertInstanceOf('\ptlis\SemanticVersion\BoundingPair\BoundingPair', $boundingPair);
        }
    }


    public function testSortAscendingMajor()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=2.0.0<=2.5.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=2.7.0<4.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(3, count($sortedCollection));
        $this->assertSame('>=1.0.0<2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>=2.0.0<=2.5.0', $sortedCollection[1]->__toString());
        $this->assertSame('>=2.7.0<4.0.0', $sortedCollection[2]->__toString());
    }


    public function testSortDescendingMajor()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=2.0.0<=2.5.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=2.7.0<4.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getDescending();

        $this->assertSame(3, count($sortedCollection));
        $this->assertSame('>=2.7.0<4.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>=2.0.0<=2.5.0', $sortedCollection[1]->__toString());
        $this->assertSame('>=1.0.0<2.0.0', $sortedCollection[2]->__toString());
    }


    public function testMatchIdentical()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getDescending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>=1.0.0<2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>=1.0.0<2.0.0', $sortedCollection[1]->__toString());
    }


    public function testMatchLowerAscending()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<1.5.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>=1.0.0<1.5.0', $sortedCollection[0]->__toString());
        $this->assertSame('>=1.0.0<2.0.0', $sortedCollection[1]->__toString());
    }


    public function testMatchLowerDescending()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<1.5.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getDescending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>=1.0.0<2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>=1.0.0<1.5.0', $sortedCollection[1]->__toString());
    }


    public function testMatchLessThanLessOrEqualToAscendingOne()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<=2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>=1.0.0<2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>=1.0.0<=2.0.0', $sortedCollection[1]->__toString());
    }


    public function testMatchLessThanLessOrEqualToAscendingTwo()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<=2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>=1.0.0<2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>=1.0.0<=2.0.0', $sortedCollection[1]->__toString());
    }


    public function testMatchLessThanLessOrEqualToDescendingOne()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<=2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getDescending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>=1.0.0<=2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>=1.0.0<2.0.0', $sortedCollection[1]->__toString());
    }


    public function testMatchLessThanLessOrEqualToDescendingTwo()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<=2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getDescending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>=1.0.0<=2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>=1.0.0<2.0.0', $sortedCollection[1]->__toString());
    }


    public function testMatchGreaterThanGreaterOrEqualToAscendingOne()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0<=2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<=2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>=1.0.0<=2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>1.0.0<=2.0.0', $sortedCollection[1]->__toString());
    }


    public function testMatchGreaterThanGreaterOrEqualToAscendingTwo()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<=2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>=1.0.0<=2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>1.0.0<=2.0.0', $sortedCollection[1]->__toString());
    }


    public function testMatchGreaterThanGreaterOrEqualToDescendingOne()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<=2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getDescending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>1.0.0<=2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>=1.0.0<=2.0.0', $sortedCollection[1]->__toString());
    }


    public function testMatchGreaterThanGreaterOrEqualToDescendingTwo()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0<=2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0<=2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getDescending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>1.0.0<=2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>=1.0.0<=2.0.0', $sortedCollection[1]->__toString());
    }


    public function testLowerDifferOmitOneUpper()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0<=2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>=1.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>1.0.0<=2.0.0', $sortedCollection[1]->__toString());
    }


    public function testLowerIdenticalOmitOneUpperAscendingOne()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0<=2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>1.0.0<=2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>1.0.0', $sortedCollection[1]->__toString());
    }


    public function testLowerIdenticalOmitOneUpperAscendingTwo()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>1.0.0<=2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>1.0.0', $sortedCollection[1]->__toString());
    }


    public function testLowerIdenticalOmitOneUpperDescendingOne()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getDescending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>1.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>1.0.0<=2.0.0', $sortedCollection[1]->__toString());
    }


    public function testLowerIdenticalOmitOneUpperDescendingTwo()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0<=2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getDescending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>1.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>1.0.0<=2.0.0', $sortedCollection[1]->__toString());
    }


    public function testLowerDifferOmitTwoUpper()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>=1.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>=1.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>1.0.0', $sortedCollection[1]->__toString());
    }


    public function testLowerIdenticalOmitTwoUpper()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('>1.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>1.0.0', $sortedCollection[1]->__toString());
    }


    public function testOmitOneLowerOne()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0<=2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('<2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('<2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>1.0.0<=2.0.0', $sortedCollection[1]->__toString());
    }


    public function testOmitOneLowerTwo()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('<2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('<2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('>1.0.0<=2.0.0', $sortedCollection[1]->__toString());
    }


    public function testOmitTwoLower()
    {
        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('<=2.0.0');
        $boundingPairList[] = $engine->parseBoundingPair('<2.0.0');

        $collection = new BoundingPairCollection();
        $collection->setList($boundingPairList);

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('<2.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('<=2.0.0', $sortedCollection[1]->__toString());
    }


    public function testClone()
    {
        $engine = new VersionEngine();

        $boundingPair1 = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $collection1 = new BoundingPairCollection();

        $collection1[] = $boundingPair1;

        $boundingPair2 = $engine->parseBoundingPair('>=2.7.0<4.0.0');

        $collection2 = clone $collection1;
        $collection2[] = $boundingPair2;


        $this->assertSame(1, count($collection1));
        $this->assertSame(2, count($collection2));
    }
}
