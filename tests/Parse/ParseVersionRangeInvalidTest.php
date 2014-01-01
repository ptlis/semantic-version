<?php

/**
 * Tests to ensure correct handling of invalid version ranges.
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
 * Tests to ensure correct handling of invalid version ranges.
 */
class ParseVersionRangeInvalidTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $inStr = '';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionRangeException',
            'The version range "' . $inStr . '" could not be parsed.'
        );

        $engine  = new VersionEngine();
        $engine->parseVersionRange($inStr);
    }


    public function testInvalidComparator()
    {
        $inStr = '=>1.0.3<=2';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionRangeException',
            'The version range "' . $inStr . '" could not be parsed.'
        );

        $engine  = new VersionEngine();
        $engine->parseVersionRange($inStr);
    }


    public function testInvalidVersion()
    {
        $inStr = '>=1.x.3<2.0.0';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidVersionRangeException',
            'The version range "' . $inStr . '" could not be parsed.'
        );

        $engine  = new VersionEngine();
        $engine->parseVersionRange($inStr);
    }
}
