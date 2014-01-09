<?php

/**
 * Tests to ensure correct error handling for invalid Versions.
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

use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct error handling for invalid Versions.
 */
class VersionInvalidTest extends \PHPUnit_Framework_TestCase
{
    public function testInvalidMajor()
    {
        $major = 'foo';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'Failed to set major version to invalid value "' . $major . '"'
        );

        $version = new Version();
        $version->setMajor($major);
    }


    public function testInvalidMinor()
    {
        $minor = 'bar';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'Failed to set minor version to invalid value "' . $minor . '"'
        );

        $version = new Version();
        $version
            ->setMajor(1)
            ->setMinor($minor);
    }


    public function testInvalidPatch()
    {
        $patch = 'baz';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'Failed to set patch version to invalid value "' . $patch . '"'
        );

        $version = new Version();
        $version
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch($patch);
    }
}