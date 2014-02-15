<?php

/**
 * Tests to validate correct use of VersionCollection.
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

namespace ptlis\SemanticVersion\Test\Collection;

use ptlis\SemanticVersion\Collection\VersionCollection;
use ptlis\SemanticVersion\Label\LabelAbsent;
use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Label\LabelRc;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to validate correct use of VersionCollection.
 */
class VersionCollectionValidTest extends \PHPUnit_Framework_TestCase
{
    public function testAddSingle()
    {
        $version = new Version();
        $version
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $collection = new VersionCollection();

        $collection[] = $version;

        $this->assertSame('1.0.0', $collection[0]->__toString());
    }


    public function testAddMulti()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;

        $this->assertSame('1.0.0', $collection[0]->__toString());
        $this->assertSame('2.0.0', $collection[1]->__toString());
    }


    public function testAddBulk()
    {
        $versionList = array();

        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());
        $versionList[] = $version1;

        $version2 = new Version();
        $version2
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());
        $versionList[] = $version2;


        $collection = new VersionCollection();

        $collection->setList($versionList);

        $this->assertSame('1.0.0', $collection[0]->__toString());
        $this->assertSame('2.0.0', $collection[1]->__toString());
    }


    public function testCount()
    {
        $version = new Version();
        $version
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $collection = new VersionCollection();

        $collection[] = $version;

        $this->assertSame(1, count($collection));
    }


    public function testRemove()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;

        unset($collection[0]);

        $this->assertSame(1, count($collection));
        $this->assertSame('2.0.0', $collection[1]->__toString());
    }


    public function testRemoveIndexDoesNotExist()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $collection = new VersionCollection();

        $collection[] = $version1;

        unset($collection[1]);

        $this->assertSame(1, count($collection));
        $this->assertSame('1.0.0', $collection[0]->__toString());
    }


    public function testToString()
    {
        $version1 = new Version();
        $version1
            ->setMajor(3)
            ->setMinor(0)
            ->setPatch(5)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(3)
            ->setPatch(4)
            ->setLabel(new LabelAbsent());

        $version3 = new Version();
        $version3
            ->setMajor(4)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelBeta());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;


        $this->assertSame(
            '3.0.5, 1.3.4, 4.1.0-beta',
            $collection->__toString()
        );
    }


    public function testIterator()
    {
        $version1 = new Version();
        $version1
            ->setMajor(3)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version3 = new Version();
        $version3
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;

        foreach ($collection as $version) {
            $this->assertInstanceOf('\ptlis\SemanticVersion\Version\VersionInterface', $version);
        }
    }


    public function testSortAscendingMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(3)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version3 = new Version();
        $version3
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;

        $sortedCollection = $collection->getAscending();

        $this->assertSame(3, count($sortedCollection));
        $this->assertSame('1.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('2.0.0', $sortedCollection[1]->__toString());
        $this->assertSame('3.0.0', $sortedCollection[2]->__toString());
    }


    public function testSortDescendingMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(3)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version3 = new Version();
        $version3
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;

        $sortedCollection = $collection->getDescending();

        $this->assertSame(3, count($sortedCollection));
        $this->assertSame('3.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('2.0.0', $sortedCollection[1]->__toString());
        $this->assertSame('1.0.0', $sortedCollection[2]->__toString());
    }


    public function testSortAscendingMinor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(10)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version3 = new Version();
        $version3
            ->setMajor(1)
            ->setMinor(3)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;

        $sortedCollection = $collection->getAscending();

        $this->assertSame(3, count($sortedCollection));
        $this->assertSame('1.0.0', $sortedCollection[0]->__toString());
        $this->assertSame('1.3.0', $sortedCollection[1]->__toString());
        $this->assertSame('1.10.0', $sortedCollection[2]->__toString());
    }


    public function testSortDescendingMinor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(10)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version3 = new Version();
        $version3
            ->setMajor(1)
            ->setMinor(3)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;

        $sortedCollection = $collection->getDescending();

        $this->assertSame(3, count($sortedCollection));
        $this->assertSame('1.10.0', $sortedCollection[0]->__toString());
        $this->assertSame('1.3.0', $sortedCollection[1]->__toString());
        $this->assertSame('1.0.0', $sortedCollection[2]->__toString());
    }


    public function testSortAscendingPatch()
    {
        $version1 = new Version();
        $version1
            ->setMajor(2)
            ->setMinor(3)
            ->setPatch(1)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(2)
            ->setMinor(3)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version3 = new Version();
        $version3
            ->setMajor(2)
            ->setMinor(3)
            ->setPatch(5)
            ->setLabel(new LabelAbsent());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;

        $sortedCollection = $collection->getAscending();

        $this->assertSame(3, count($sortedCollection));
        $this->assertSame('2.3.0', $sortedCollection[0]->__toString());
        $this->assertSame('2.3.1', $sortedCollection[1]->__toString());
        $this->assertSame('2.3.5', $sortedCollection[2]->__toString());
    }


    public function testSortDescendingPatch()
    {
        $version1 = new Version();
        $version1
            ->setMajor(2)
            ->setMinor(3)
            ->setPatch(1)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(2)
            ->setMinor(3)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version3 = new Version();
        $version3
            ->setMajor(2)
            ->setMinor(3)
            ->setPatch(5)
            ->setLabel(new LabelAbsent());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;

        $sortedCollection = $collection->getDescending();

        $this->assertSame(3, count($sortedCollection));
        $this->assertSame('2.3.5', $sortedCollection[0]->__toString());
        $this->assertSame('2.3.1', $sortedCollection[1]->__toString());
        $this->assertSame('2.3.0', $sortedCollection[2]->__toString());
    }


    public function testSortAscendingLabel()
    {
        $version1 = new Version();
        $version1
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelRc());

        $version2 = new Version();
        $version2
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelAlpha());

        $version3 = new Version();
        $version3
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelBeta());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;

        $sortedCollection = $collection->getAscending();

        $this->assertSame(3, count($sortedCollection));
        $this->assertSame('3.1.0-alpha', $sortedCollection[0]->__toString());
        $this->assertSame('3.1.0-beta', $sortedCollection[1]->__toString());
        $this->assertSame('3.1.0-rc', $sortedCollection[2]->__toString());
    }


    public function testSortDescendingLabel()
    {
        $version1 = new Version();
        $version1
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelRc());

        $version2 = new Version();
        $version2
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelAlpha());

        $version3 = new Version();
        $version3
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelBeta());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;

        $sortedCollection = $collection->getDescending();

        $this->assertSame(3, count($sortedCollection));
        $this->assertSame('3.1.0-rc', $sortedCollection[0]->__toString());
        $this->assertSame('3.1.0-beta', $sortedCollection[1]->__toString());
        $this->assertSame('3.1.0-alpha', $sortedCollection[2]->__toString());
    }


    public function testSortAscendingLabelNumber()
    {
        $version1 = new Version();
        $version1
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelRc(5));

        $version2 = new Version();
        $version2
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelRc(2));

        $version3 = new Version();
        $version3
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelRc(3));

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;

        $sortedCollection = $collection->getAscending();

        $this->assertSame(3, count($sortedCollection));
        $this->assertSame('3.1.0-rc.2', $sortedCollection[0]->__toString());
        $this->assertSame('3.1.0-rc.3', $sortedCollection[1]->__toString());
        $this->assertSame('3.1.0-rc.5', $sortedCollection[2]->__toString());
    }


    public function testSortDescendingLabelNumber()
    {
        $version1 = new Version();
        $version1
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelRc(5));

        $version2 = new Version();
        $version2
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelRc(2));

        $version3 = new Version();
        $version3
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelRc(3));

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;

        $sortedCollection = $collection->getDescending();

        $this->assertSame(3, count($sortedCollection));
        $this->assertSame('3.1.0-rc.5', $sortedCollection[0]->__toString());
        $this->assertSame('3.1.0-rc.3', $sortedCollection[1]->__toString());
        $this->assertSame('3.1.0-rc.2', $sortedCollection[2]->__toString());
    }


    public function testSortAscendingIdenticalVersions()
    {
        $version1 = new Version();
        $version1
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelRc(5));

        $version2 = new Version();
        $version2
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelRc(5));

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;

        $sortedCollection = $collection->getAscending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('3.1.0-rc.5', $sortedCollection[0]->__toString());
        $this->assertSame('3.1.0-rc.5', $sortedCollection[1]->__toString());
    }


    public function testSortDescendingIdenticalVersions()
    {
        $version1 = new Version();
        $version1
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelRc(5));

        $version2 = new Version();
        $version2
            ->setMajor(3)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelRc(5));

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;

        $sortedCollection = $collection->getDescending();

        $this->assertSame(2, count($sortedCollection));
        $this->assertSame('3.1.0-rc.5', $sortedCollection[0]->__toString());
        $this->assertSame('3.1.0-rc.5', $sortedCollection[1]->__toString());
    }


    public function testClone()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $collection1 = new VersionCollection();

        $collection1[] = $version1;

        $version2 = new Version();
        $version2
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $collection2 = clone $collection1;
        $collection2[] = $version2;


        $this->assertSame(1, count($collection1));
        $this->assertSame(2, count($collection2));
    }


    public function testInRange()
    {
        $version1 = new Version();
        $version1
            ->setMajor(3)
            ->setMinor(0)
            ->setPatch(5)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(3)
            ->setPatch(4)
            ->setLabel(new LabelAbsent());

        $version3 = new Version();
        $version3
            ->setMajor(4)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelBeta());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;

        $testVersion = new Version();
        $testVersion
            ->setMajor(1)
            ->setMinor(3)
            ->setPatch(4)
            ->setLabel(new LabelAbsent());

        $this->assertTrue($collection->isSatisfiedBy($testVersion));
    }


    public function testNotInRange()
    {
        $version1 = new Version();
        $version1
            ->setMajor(3)
            ->setMinor(0)
            ->setPatch(5)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(3)
            ->setPatch(4)
            ->setLabel(new LabelAbsent());

        $version3 = new Version();
        $version3
            ->setMajor(4)
            ->setMinor(1)
            ->setPatch(0)
            ->setLabel(new LabelBeta());

        $collection = new VersionCollection();

        $collection[] = $version1;
        $collection[] = $version2;
        $collection[] = $version3;

        $testVersion = new Version();
        $testVersion
            ->setMajor(1)
            ->setMinor(3)
            ->setPatch(5)
            ->setLabel(new LabelAbsent());

        $this->assertFalse($collection->isSatisfiedBy($testVersion));
    }
}
