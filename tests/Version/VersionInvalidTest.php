<?php

/**
 * Tests to ensure correct error handling for invalid Versions.
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

namespace ptlis\SemanticVersion\Test\Version;

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


    public function testMajorMaxInt()
    {
        // 32bit
        if (PHP_INT_SIZE === 4) {
            $major = "2147483647";

        // 64bit
        } else {
            $major = "9223372036854775807";
        }

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'Major version number is larger than PHP\'s max int "' . $major . '"'
        );

        $version = new Version();
        $version
            ->setMajor($major);
    }


    public function testMajorAboveMaxInt()
    {
        // 32bit
        if (PHP_INT_SIZE === 4) {
            $major = "2147483648";

        // 64bit
        } else {
            $major = "9223372036854775808";
        }

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'Major version number is larger than PHP\'s max int "' . $major . '"'
        );

        $version = new Version();
        $version
            ->setMajor($major);
    }


    public function testMinorMaxInt()
    {
        // 32bit
        if (PHP_INT_SIZE === 4) {
            $minor = "2147483647";

        // 64bit
        } else {
            $minor = "9223372036854775807";
        }

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'Minor version number is larger than PHP\'s max int "' . $minor . '"'
        );

        $version = new Version();
        $version
            ->setMajor(1)
            ->setMinor($minor);
    }


    public function testMinorAboveMaxInt()
    {
        // 32bit
        if (PHP_INT_SIZE === 4) {
            $minor = "2147483648";

        // 64bit
        } else {
            $minor = "9223372036854775808";
        }

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'Minor version number is larger than PHP\'s max int "' . $minor . '"'
        );

        $version = new Version();
        $version
            ->setMajor(1)
            ->setMinor($minor);
    }


    public function testPatchMaxInt()
    {
        // 32bit
        if (PHP_INT_SIZE === 4) {
            $patch = "2147483647";

        // 64bit
        } else {
            $patch = "9223372036854775807";
        }

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'Patch version number is larger than PHP\'s max int "' . $patch . '"'
        );

        $version = new Version();
        $version
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch($patch);
    }


    public function testPatchAboveMaxInt()
    {
        // 32bit
        if (PHP_INT_SIZE === 4) {
            $patch = "2147483648";

        // 64bit
        } else {
            $patch = "9223372036854775808";
        }

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'Patch version number is larger than PHP\'s max int "' . $patch . '"'
        );

        $version = new Version();
        $version
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch($patch);
    }
}
