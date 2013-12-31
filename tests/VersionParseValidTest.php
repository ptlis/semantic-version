<?php

/**
 * Tests to ensure correct parsing of valid version numbers.
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
 * Tests to ensure correct parsing of valid version numbers.
 */
class VersionParseValidTest extends \PHPUnit_Framework_TestCase
{
    public function testMajor()
    {
        $inVersion = '1';
        $expectedVersion = '1.0.0';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectedVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(0, $outVersion->getMinor());
        $this->assertSame(0, $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testMajorMinor()
    {
        $inVersion = '1.5';
        $expectedVersion = '1.5.0';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertTrue($valid);
        $this->assertNotNull($outVersion);
        $this->assertSame($expectedVersion, $outVersion->__toString());

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame(0, $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testMajorMinorPatch()
    {
        $inVersion = '1.5.3';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($inVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame(3, $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testMajorMinorPatchAlpha()
    {
        $inVersion = '1.5.0-alpha';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($inVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame(0, $outVersion->getPatch());
        $this->assertSame('alpha', $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_ALPHA, $outVersion->getLabelPrecedence());
    }


    public function testMajorMinorPatchAlphaNum()
    {
        $inVersion = '1.5.0-alpha.2';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($inVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame(0, $outVersion->getPatch());
        $this->assertSame('alpha.2', $outVersion->getLabel());
        $this->assertSame(2, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_ALPHA, $outVersion->getLabelPrecedence());
    }


    public function testMajorMinorPatchBeta()
    {
        $inVersion = '1.5.0-beta';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($inVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame(0, $outVersion->getPatch());
        $this->assertSame('beta', $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_BETA, $outVersion->getLabelPrecedence());
    }


    public function testMajorMinorPatchBetaNum()
    {
        $inVersion = '1.5.0-beta.2';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($inVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame(0, $outVersion->getPatch());
        $this->assertSame('beta.2', $outVersion->getLabel());
        $this->assertSame(2, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_BETA, $outVersion->getLabelPrecedence());
    }


    public function testMajorMinorPatchRc()
    {
        $inVersion = '1.5.0-rc';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($inVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame(0, $outVersion->getPatch());
        $this->assertSame('rc', $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_RC, $outVersion->getLabelPrecedence());
    }


    public function testMajorMinorPatchRcNum()
    {
        $inVersion = '1.5.0-rc.2';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($inVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame(0, $outVersion->getPatch());
        $this->assertSame('rc.2', $outVersion->getLabel());
        $this->assertSame(2, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_RC, $outVersion->getLabelPrecedence());
    }




    public function testMajorWithPadding()
    {
        $inVersion = '01';
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


    public function testMinorWithPadding()
    {
        $inVersion = '1.05';
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


    public function testPatchWithPadding()
    {
        $inVersion = '1.05.03';
        $expectVersion = '1.5.3';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame(3, $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testWithWhitespace()
    {
        $inVersion = '  1.5.3 ';
        $expectVersion = '1.5.3';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame(3, $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testPatchWildcardX()
    {
        $inVersion = '1.5.x';
        $expectVersion = '1.5.*';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame('*', $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testMinorWildcardX()
    {
        $inVersion = '1.x';
        $expectVersion = '1.*.*';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame('*', $outVersion->getMinor());
        $this->assertSame('*', $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testMinorPatchWildcardX()
    {
        $inVersion = '1.x.x';
        $expectVersion = '1.*.*';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame('*', $outVersion->getMinor());
        $this->assertSame('*', $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testMajorWildcardX()
    {
        $inVersion = 'x';
        $expectVersion = '*.*.*';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame('*', $outVersion->getMajor());
        $this->assertSame('*', $outVersion->getMinor());
        $this->assertSame('*', $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testMajorMinorWildcardX()
    {
        $inVersion = 'x.x';
        $expectVersion = '*.*.*';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame('*', $outVersion->getMajor());
        $this->assertSame('*', $outVersion->getMinor());
        $this->assertSame('*', $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testMajorMinorPatchWildcardX()
    {
        $inVersion = 'x.x.x';
        $expectVersion = '*.*.*';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame('*', $outVersion->getMajor());
        $this->assertSame('*', $outVersion->getMinor());
        $this->assertSame('*', $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testPatchWildcardStar()
    {
        $inVersion = '1.5.*';
        $expectVersion = '1.5.*';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame(5, $outVersion->getMinor());
        $this->assertSame('*', $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testMinorWildcardStar()
    {
        $inVersion = '1.*';
        $expectVersion = '1.*.*';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame('*', $outVersion->getMinor());
        $this->assertSame('*', $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testMinorPatchWildcardStar()
    {
        $inVersion = '1.*.*';
        $expectVersion = '1.*.*';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame(1, $outVersion->getMajor());
        $this->assertSame('*', $outVersion->getMinor());
        $this->assertSame('*', $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testMajorWildcardStar()
    {
        $inVersion = '*';
        $expectVersion = '*.*.*';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame('*', $outVersion->getMajor());
        $this->assertSame('*', $outVersion->getMinor());
        $this->assertSame('*', $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testMajorMinorWildcardStar()
    {
        $inVersion = '*.*';
        $expectVersion = '*.*.*';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame('*', $outVersion->getMajor());
        $this->assertSame('*', $outVersion->getMinor());
        $this->assertSame('*', $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }


    public function testMajorMinorPatchWildcardStar()
    {
        $inVersion = '*.*.*';
        $expectVersion = '*.*.*';
        $outVersion = VersionEngine::parseVersion($inVersion);
        $valid = VersionEngine::validVersion($inVersion);

        $this->assertNotNull($outVersion);
        $this->assertSame($expectVersion, $outVersion->__toString());
        $this->assertTrue($valid);

        $this->assertSame('*', $outVersion->getMajor());
        $this->assertSame('*', $outVersion->getMinor());
        $this->assertSame('*', $outVersion->getPatch());
        $this->assertSame(null, $outVersion->getLabel());
        $this->assertSame(0, $outVersion->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outVersion->getLabelPrecedence());
    }
}
