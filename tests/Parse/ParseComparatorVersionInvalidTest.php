<?php

/**
 * Tests to ensure correct handling of invalid comparator version numbers.
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
 * Tests to ensure correct handling of invalid comparator version numbers.
 */
class ParseComparatorVersionInvalidTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $inStr = '';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidComparatorVersionException',
            'The comparator version "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseComparatorVersion($inStr);
    }


    public function testInvalidComparator()
    {
        $inStr = '=>1.0.3';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidComparatorVersionException',
            'The comparator version "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseComparatorVersion($inStr);
    }


    public function testInvalidVersion()
    {
        $inStr = '>=1.x.3';

        $this->setExpectedException(
            '\ptlis\SemanticVersion\Exception\InvalidComparatorVersionException',
            'The comparator version "' . $inStr . '" could not be parsed.'
        );

        $engine = new VersionEngine();
        $engine->parseComparatorVersion($inStr);
    }
}
