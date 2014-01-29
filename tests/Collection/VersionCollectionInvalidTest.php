<?php

/**
 * Tests to ensure correct handling of invalid use of VersionCollection.
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

use ptlis\SemanticVersion\Collection\VersionCollection;
use ptlis\SemanticVersion\Label\LabelAbsent;
use ptlis\SemanticVersion\Version\Version;
use StdClass;

/**
 * Tests to ensure correct handling of invalid use of VersionCollection.
 */
class VersionCollectionInvalidTest extends \PHPUnit_Framework_TestCase
{
    public function testAddNonVersionInterface()
    {
        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\SemanticVersionException',
            'A VersionCollection may only store objects implementing VersionInterface.'
        );

        $version = new \StdClass();

        $collection = new VersionCollection();

        $collection[] = $version;
    }


    public function testAddBulkNonVersionInterface()
    {
        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\SemanticVersionException',
            'A VersionCollection may only store objects implementing VersionInterface.'
        );

        $versionList = array();

        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());
        $versionList[] = $version1;

        $version2 = new StdClass();
        $versionList[] = $version2;

        $collection = new VersionCollection();

        $collection->setList($versionList);
    }


    public function testInvalidOffset()
    {
        $this->setExpectedException(
            '\OutOfBoundsException'
        );

        $version = new Version();
        $version
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $collection = new VersionCollection();

        $collection[] = $version;

        $collection[1]->__toString();
    }
}
