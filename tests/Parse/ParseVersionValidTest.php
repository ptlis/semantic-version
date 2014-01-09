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

namespace tests\Parse;

use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Label\LabelDev;
use ptlis\SemanticVersion\Label\LabelNone;
use ptlis\SemanticVersion\Label\LabelRc;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct parsing of valid version numbers.
 */
class ParseVersionValidTest extends \PHPUnit_Framework_TestCase
{
    public function testAllFieldsLabelNone()
    {
        $inStr = '1.0.0';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.0.0';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);

        $this->assertEquals($expectVersion->getMajor(), $outVersion->getMajor());
        $this->assertEquals($expectVersion->getMinor(), $outVersion->getMinor());
        $this->assertEquals($expectVersion->getPatch(), $outVersion->getPatch());
        $this->assertEquals($expectVersion->getLabel(), $outVersion->getLabel());

        $this->assertEquals($expectVersion->getLabel()->getName(), $outVersion->getLabel()->getName());
        $this->assertEquals($expectVersion->getLabel()->getVersion(), $outVersion->getLabel()->getVersion());
        $this->assertEquals($expectVersion->getLabel()->getPrecedence(), $outVersion->getLabel()->getPrecedence());
    }


    public function testAllFieldsLabelAlpha()
    {
        $inStr = '1.0.0-alpha.1';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.0.0-alpha.1';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAlpha(1));

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);

        $this->assertEquals($expectVersion->getMajor(), $outVersion->getMajor());
        $this->assertEquals($expectVersion->getMinor(), $outVersion->getMinor());
        $this->assertEquals($expectVersion->getPatch(), $outVersion->getPatch());
        $this->assertEquals($expectVersion->getLabel(), $outVersion->getLabel());

        $this->assertEquals($expectVersion->getLabel()->getName(), $outVersion->getLabel()->getName());
        $this->assertEquals($expectVersion->getLabel()->getVersion(), $outVersion->getLabel()->getVersion());
        $this->assertEquals($expectVersion->getLabel()->getPrecedence(), $outVersion->getLabel()->getPrecedence());
    }


    public function testAllFieldsLabelBeta()
    {
        $inStr = '1.0.0-beta.12';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.0.0-beta.12';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelBeta(12));

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);

        $this->assertEquals($expectVersion->getMajor(), $outVersion->getMajor());
        $this->assertEquals($expectVersion->getMinor(), $outVersion->getMinor());
        $this->assertEquals($expectVersion->getPatch(), $outVersion->getPatch());
        $this->assertEquals($expectVersion->getLabel(), $outVersion->getLabel());

        $this->assertEquals($expectVersion->getLabel()->getName(), $outVersion->getLabel()->getName());
        $this->assertEquals($expectVersion->getLabel()->getVersion(), $outVersion->getLabel()->getVersion());
        $this->assertEquals($expectVersion->getLabel()->getPrecedence(), $outVersion->getLabel()->getPrecedence());
    }


    public function testAllFieldsLabelRc()
    {
        $inStr = '1.0.0-rc.4';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.0.0-rc.4';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc(4));

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);

        $this->assertEquals($expectVersion->getMajor(), $outVersion->getMajor());
        $this->assertEquals($expectVersion->getMinor(), $outVersion->getMinor());
        $this->assertEquals($expectVersion->getPatch(), $outVersion->getPatch());
        $this->assertEquals($expectVersion->getLabel(), $outVersion->getLabel());

        $this->assertEquals($expectVersion->getLabel()->getName(), $outVersion->getLabel()->getName());
        $this->assertEquals($expectVersion->getLabel()->getVersion(), $outVersion->getLabel()->getVersion());
        $this->assertEquals($expectVersion->getLabel()->getPrecedence(), $outVersion->getLabel()->getPrecedence());
    }


    public function testMajor()
    {
        $inStr = '1';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.0.0';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinor()
    {
        $inStr = '1.5';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.0';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatch()
    {
        $inStr = '1.5.3';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.3';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchDevelopment()
    {
        $inStr = '1.5.0-dev';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.0-dev';
        $expectVersion = new Version();

        $expectLabel = new LabelDev();
        $expectLabel->setName('dev');

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel($expectLabel);

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchAlpha()
    {
        $inStr = '1.5.0-alpha';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.0-alpha';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelAlpha());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchAlphaNum()
    {
        $inStr = '1.5.0-alpha.2';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.0-alpha.2';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelAlpha(2));

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchBeta()
    {
        $inStr = '1.5.0-beta';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.0-beta';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelBeta());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchBetaNum()
    {
        $inStr = '1.5.0-beta.2';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.0-beta.2';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelBeta(2));

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchRc()
    {
        $inStr = '1.5.0-rc';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.0-rc';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelRc());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchRcNum()
    {
        $inStr = '1.5.0-rc.2';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.0-rc.2';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelRc(2));

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }




    public function testMajorWithPadding()
    {
        $inStr = '01';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.0.0';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMinorWithPadding()
    {
        $inStr = '1.05';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.0';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testPatchWithPadding()
    {
        $inStr = '1.05.03';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.3';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testWithBuildMetadata()
    {
        $inStr = '1.5.3-alpha.5+2014-01-09.1';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.3-alpha.5+2014-01-09.1';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3)
            ->setLabel(new LabelAlpha(5, '2014-01-09.1'));

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testPatchWildcardX()
    {
        $inStr = '1.5.x';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch('*')
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMinorWildcardX()
    {
        $inStr = '1.x';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMinorPatchWildcardX()
    {
        $inStr = '1.x.x';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorWildcardX()
    {
        $inStr = 'x';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '*.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('*')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorWildcardX()
    {
        $inStr = 'x.x';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '*.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('*')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchWildcardX()
    {
        $inStr = 'x.x.x';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '*.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('*')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testPatchWildcardStar()
    {
        $inStr = '1.5.*';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('1')
            ->setMinor('5')
            ->setPatch('*')
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMinorWildcardStar()
    {
        $inStr = '1.*';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('1')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMinorPatchWildcardStar()
    {
        $inStr = '1.*.*';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('1')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorWildcardStar()
    {
        $inStr = '*';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '*.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('*')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorWildcardStar()
    {
        $inStr = '*.*';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '*.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('*')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchWildcardStar()
    {
        $inStr = '*.*.*';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '*.*.*';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor('*')
            ->setMinor('*')
            ->setPatch('*')
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }
}
