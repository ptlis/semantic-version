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
class ParseVersionValidTest extends \PHPUnit_Framework_TestCase
{
    public function testMajor()
    {
        $inStr = '1';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.0.0';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinor()
    {
        $inStr = '1.5';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.5.0';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatch()
    {
        $inStr = '1.5.3';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.5.3';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchAlpha()
    {
        $inStr = '1.5.0-alpha';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.5.0-alpha';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel('alpha')
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_ALPHA);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchAlphaNum()
    {
        $inStr = '1.5.0-alpha.2';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.5.0-alpha.2';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel('alpha.2')
            ->setLabelNumber(2)
            ->setLabelPrecedence(Version::LABEL_ALPHA);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchBeta()
    {
        $inStr = '1.5.0-beta';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.5.0-beta';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel('beta')
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_BETA);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchBetaNum()
    {
        $inStr = '1.5.0-beta.2';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.5.0-beta.2';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel('beta.2')
            ->setLabelNumber(2)
            ->setLabelPrecedence(Version::LABEL_BETA);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchRc()
    {
        $inStr = '1.5.0-rc';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.5.0-rc';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel('rc')
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_RC);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchRcNum()
    {
        $inStr = '1.5.0-rc.2';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.5.0-rc.2';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel('rc.2')
            ->setLabelNumber(2)
            ->setLabelPrecedence(Version::LABEL_RC);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }




    public function testMajorWithPadding()
    {
        $inStr = '01';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.0.0';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMinorWithPadding()
    {
        $inStr = '1.05';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.5.0';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testPatchWithPadding()
    {
        $inStr = '1.05.03';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.5.3';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testWithWhitespace()
    {
        $inStr = '  1.5.3 ';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.5.3';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testPatchWildcardX()
    {
        $inStr = '1.5.x';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.5.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch('*')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMinorWildcardX()
    {
        $inStr = '1.x';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMinorPatchWildcardX()
    {
        $inStr = '1.x.x';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorWildcardX()
    {
        $inStr = 'x';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '*.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('*')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorWildcardX()
    {
        $inStr = 'x.x';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '*.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('*')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchWildcardX()
    {
        $inStr = 'x.x.x';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '*.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('*')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testPatchWildcardStar()
    {
        $inStr = '1.5.*';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.5.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('1')
            ->setMinor('5')
            ->setPatch('*')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMinorWildcardStar()
    {
        $inStr = '1.*';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('1')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMinorPatchWildcardStar()
    {
        $inStr = '1.*.*';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '1.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('1')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorWildcardStar()
    {
        $inStr = '*';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '*.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('*')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorWildcardStar()
    {
        $inStr = '*.*';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '*.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('*')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchWildcardStar()
    {
        $inStr = '*.*.*';

        $outVersion = VersionEngine::parseVersion($inStr);

        $expectStr = '*.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('*')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }
}
