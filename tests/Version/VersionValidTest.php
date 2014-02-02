<?php

/**
 * Tests to validate correct use of Version classes.
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

namespace tests\Version;

use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to validate correct use of Version classes.
 */
class VersionValidTest extends \PHPUnit_Framework_TestCase
{
    public function testCloneOne()
    {
        $versionOne = new Version();
        $versionOne
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelAlpha());

        $versionTwo = clone $versionOne;
        $versionTwo->getLabel()->setVersion(5);

        $this->assertNotSame($versionOne, $versionTwo);
        $this->assertSame(0, $versionOne->getLabel()->getVersion());
        $this->assertSame(5, $versionTwo->getLabel()->getVersion());
    }


    public function testBelowMaxInt()
    {
        // 32bit
        if (PHP_INT_SIZE === 4) {
            $major = "2147483646";

        // 64bit
        } else {
            $major = "9223372036854775806";
        }

        $version = new Version();
        $version
            ->setMajor($major);

        $this->assertSame($major .'.0.0', $version->__toString());
    }
}
