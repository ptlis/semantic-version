<?php

/**
 * Tests to ensure correct handling of invalid version numbers.
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

use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct handling of invalid version numbers.
 */
class VersionParseInvalidTest extends \PHPUnit_Framework_TestCase
{
    public function testGitBranch()
    {
        $inVersion = 'dev-master';

        $valid = VersionEngine::validVersion($inVersion);
        $this->assertFalse($valid);

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inVersion . '" could not be parsed.'
        );
        VersionEngine::parseVersion($inVersion);
    }


    public function testEmpty()
    {
        $inVersion = '';

        $valid = VersionEngine::validVersion($inVersion);
        $this->assertFalse($valid);

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inVersion . '" could not be parsed.'
        );
        VersionEngine::parseVersion($inVersion);
    }


    public function testTooManyDigits()
    {
        $inVersion = '1.2.3.4';

        $valid = VersionEngine::validVersion($inVersion);
        $this->assertFalse($valid);

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inVersion . '" could not be parsed.'
        );
        VersionEngine::parseVersion($inVersion);
    }


    public function testTooManyDigitsWildcard()
    {
        $inVersion = '1.2.3.*';

        $valid = VersionEngine::validVersion($inVersion);
        $this->assertFalse($valid);

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inVersion . '" could not be parsed.'
        );
        VersionEngine::parseVersion($inVersion);
    }


    public function testFullOmittedMinor()
    {
        $inVersion = '1..3';

        $valid = VersionEngine::validVersion($inVersion);
        $this->assertFalse($valid);

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inVersion . '" could not be parsed.'
        );
        VersionEngine::parseVersion($inVersion);
    }


    public function testFullOmittedMajor()
    {
        $inVersion = '.2.3';

        $valid = VersionEngine::validVersion($inVersion);
        $this->assertFalse($valid);

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inVersion . '" could not be parsed.'
        );
        VersionEngine::parseVersion($inVersion);
    }


    public function testNonDigit()
    {
        $inVersion = '1.foo.3';

        $valid = VersionEngine::validVersion($inVersion);
        $this->assertFalse($valid);

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inVersion . '" could not be parsed.'
        );
        VersionEngine::parseVersion($inVersion);
    }


    public function testWildcardAndDigit()
    {
        $inVersion = '1.2x.3';

        $valid = VersionEngine::validVersion($inVersion);
        $this->assertFalse($valid);

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inVersion . '" could not be parsed.'
        );
        VersionEngine::parseVersion($inVersion);
    }


    public function testMajorWildcard()
    {
        $inVersion = '*.2.3';

        $valid = VersionEngine::validVersion($inVersion);
        $this->assertFalse($valid);

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inVersion . '" could not be parsed.'
        );
        VersionEngine::parseVersion($inVersion);
    }


    public function testMinorWildcard()
    {
        $inVersion = '1.*.3';

        $valid = VersionEngine::validVersion($inVersion);
        $this->assertFalse($valid);

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inVersion . '" could not be parsed.'
        );
        VersionEngine::parseVersion($inVersion);
    }


    public function testMultipleWildcard()
    {
        $inVersion = '1.*x';

        $valid = VersionEngine::validVersion($inVersion);
        $this->assertFalse($valid);

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inVersion . '" could not be parsed.'
        );
        VersionEngine::parseVersion($inVersion);
    }


    public function testMultipleWildcardOnly()
    {
        $inVersion = '*x*';

        $valid = VersionEngine::validVersion($inVersion);
        $this->assertFalse($valid);

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inVersion . '" could not be parsed.'
        );
        VersionEngine::parseVersion($inVersion);
    }
}
