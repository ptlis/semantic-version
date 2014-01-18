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

namespace tests\Parse;

use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct handling of invalid version numbers.
 */
class ParseVersionInvalidTest extends \PHPUnit_Framework_TestCase
{
    public function testGitBranch()
    {
        $inStr = 'dev-master';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }


    public function testEmpty()
    {
        $inStr = '';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }


    public function testTooManyDigits()
    {
        $inStr = '1.2.3.4';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }


    public function testTooManyDigitsWildcard()
    {
        $inStr = '1.2.3.*';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }


    public function testFullOmittedMinor()
    {
        $inStr = '1..3';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }


    public function testFullOmittedMajor()
    {
        $inStr = '.2.3';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }


    public function testWildcardAndDigit()
    {
        $inStr = '1.2x.3';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }


    public function testMajorWildcard()
    {
        $inStr = '*.2.3';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }


    public function testMinorWildcard()
    {
        $inStr = '1.*.3';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }


    public function testMultipleWildcard()
    {
        $inStr = '1.*x';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }


    public function testMultipleWildcardOnly()
    {
        $inStr = '*x*';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }


    public function testDotInPlaceOfHyphen()
    {
        $inStr = '3.2.0.beta1';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }


    public function testHyphenInLabel()
    {
        // Note that this is a valid semantic version number per section 9, however a hyphen here is awkward to deal
        // with when parsing a bounding pair version-range (ie 1.7.2-2.0.0)
        $inStr = '1.0.0-beta-17';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }


    public function testNumberedLabel()
    {
        // Note that this is a valid semantic version number per section 9, however a hyphen here is awkward to deal
        // with when parsing a bounding pair version-range (ie 1.7.2-2.0.0)
        $inStr = '1.0.0-0.3.7';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The version number "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseVersion($inStr);
    }
}
