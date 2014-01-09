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

namespace tests\Parse;

use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Label\LabelNone;
use ptlis\SemanticVersion\Label\LabelRc;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct parsing of malformed but usable version numbers.
 */
class ParseVersionMalformedTest extends \PHPUnit_Framework_TestCase
{
    public function testTrailingMajorDot()
    {
        $inStr = '1.';

        $engine = new VersionEngine();
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


    public function testTrailingMinorDot()
    {
        $inStr = '1.5.';

        $engine = new VersionEngine();
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


    public function testTrailingLabelHyphen()
    {
        $inStr = '1.5.0-';

        $engine = new VersionEngine();
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


    public function testOmittedLabelNumberDot()
    {
        $inStr = '1.5.0-rc1';

        $engine = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '1.5.0-rc.1';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelRc(1));

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testWithWhitespace()
    {
        $inStr = '  1.5.3 ';

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


    public function testMajorLabelOmittedHyphen()
    {
        $inStr = '3beta1';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '3.0.0-beta.1';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(3)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelBeta(1));

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorLabelOmittedHyphen()
    {
        $inStr = '2.5alpha';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '2.5.0-alpha';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(2)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelAlpha());

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }


    public function testMajorMinorPatchLabelOmittedHyphen()
    {
        $inStr = '4.7.2rc4';

        $engine  = new VersionEngine();
        $outVersion = $engine->parseVersion($inStr);

        $expectStr = '4.7.2-rc.4';
        $expectVersion = new Version();

        $expectVersion
            ->setMajor(4)
            ->setMinor(7)
            ->setPatch(2)
            ->setLabel(new LabelRc(4));

        $this->assertSame($expectStr, $outVersion->__toString());
        $this->assertEquals($expectVersion, $outVersion);
    }
}
