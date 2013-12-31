<?php

/**
 * Tests to ensure correct parsing of malformed but usable version numbers.
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

use ptlis\SemanticVersion\Entity\Version;
use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct parsing of malformed but usable version numbers.
 */
class VersionParseMalformedTest extends \PHPUnit_Framework_TestCase
{
    public function testTrailingMajorDot()
    {
        $inVersion = '1.';
        $expectVersion = '1.0.0';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(0, $outVersion->getMinor());
        $this->assertSame(0, $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testTrailingMinorDot()
    {
        $inVersion = '1.5.';
        $expectVersion = '1.5.0';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame(0, $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testTrailingLabelHyphen()
    {
        $inVersion = '1.5.0-';
        $expectVersion = '1.5.0';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame(0, $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testOmittedLabelNumberDot()
    {
        $inVersion = '1.5.0-rc1';
        $expectVersion = '1.5.0-rc1';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame(0, $outVersion->getPatch());
        $this->assertSame('rc1', $outVersion->getLabel());
        $this->assertSame(1, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_RC, $outVersion->getLabelPrecedence());
    }
}
