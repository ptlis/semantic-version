<?php

/**
 * Tests to ensure correct handling of invalid use of BoundingPairCollection.
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

use ptlis\SemanticVersion\Collection\BoundingPairCollection;
use ptlis\SemanticVersion\Label\LabelAbsent;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionEngine;
use StdClass;

/**
 * Tests to ensure correct handling of invalid use of BoundingPairCollection.
 */
class BoundingPairCollectionInvalidTest extends \PHPUnit_Framework_TestCase
{
    public function testAddNonVersionInterface()
    {
        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\SemanticVersionException',
            'A BoundingPairCollection may only store objects implementing BoundingPair.'
        );

        $version = new \StdClass();

        $collection = new BoundingPairCollection();

        $collection[] = $version;
    }


    public function testAddBulkNonVersionInterface()
    {
        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\SemanticVersionException',
            'A BoundingPairCollection may only store objects implementing BoundingPair.'
        );

        $boundingPairList = array();

        $engine = new VersionEngine();

        $boundingPairList[] = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $versionList[] = new StdClass();

        $collection = new BoundingPairCollection();

        $collection->setList($versionList);
    }


    public function testInvalidOffset()
    {
        $this->setExpectedException(
            '\OutOfBoundsException'
        );

        $engine = new VersionEngine();

        $boundingPair = $engine->parseBoundingPair('>1.0.0<=2.0.0');

        $collection = new BoundingPairCollection();

        $collection[] = $boundingPair;

        $collection[1]->__toString();
    }
}
