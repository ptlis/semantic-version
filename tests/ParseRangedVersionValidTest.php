<?php

/**
 * Tests to ensure correct parsing of valid version ranges.
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

use ptlis\SemanticVersion\Entity\RangedVersion;
use ptlis\SemanticVersion\Entity\Version;
use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct parsing of valid version ranges.
 */
class ParseRangedVersionValidTest extends \PHPUnit_Framework_TestCase
{
    public function testAllFields()
    {
        $inStr = '>1.0.0';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '>1.0.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::GREATER_THAN)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);

        $this->assertEquals($expectRangedVersion->getComparator(), $outRangedVersion->getComparator());
        $this->assertEquals($expectRangedVersion->getVersion(), $outRangedVersion->getVersion());
    }

    public function testGreaterThanMajor()
    {
        $inStr = '>1';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '>1.0.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::GREATER_THAN)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testGreaterThanMajorMinor()
    {
        $inStr = '>1.7';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '>1.7.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::GREATER_THAN)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testGreaterThanMajorMinorPatch()
    {
        $inStr = '>1.7.9';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '>1.7.9';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::GREATER_THAN)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testGreaterOrEqualToMajor()
    {
        $inStr = '>=1';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '>=1.0.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testGreaterOrEqualToMajorMinor()
    {
        $inStr = '>=1.7';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '>=1.7.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testGreaterOrEqualToMajorMinorPatch()
    {
        $inStr = '>=1.7.9';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '>=1.7.9';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testLessThanMajor()
    {
        $inStr = '<1';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '<1.0.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::LESS_THAN)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testLessThanMajorMinor()
    {
        $inStr = '<1.7';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '<1.7.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::LESS_THAN)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testLessThanMajorMinorPatch()
    {
        $inStr = '<1.7.9';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '<1.7.9';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::LESS_THAN)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testLessOrEqualToMajor()
    {
        $inStr = '<=1';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '<=1.0.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::LESS_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testLessOrEqualToMajorMinor()
    {
        $inStr = '<=1.7';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '<=1.7.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::LESS_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testLessOrEqualToMajorMinorPatch()
    {
        $inStr = '<=1.7.9';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '<=1.7.9';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::LESS_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testEqualToMajor()
    {
        $inStr = '=1';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '=1.0.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testEqualToMajorMinor()
    {
        $inStr = '=1.7';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '=1.7.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testEqualToMajorMinorPatch()
    {
        $inStr = '=1.7.9';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '=1.7.9';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }

    // TODO: labels
    // TODO: numbered labels






    public function testMajorWithPadding()
    {
        $inStr = '<01';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '<1.0.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::LESS_THAN)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testMinorWithPadding()
    {
        $inStr = '>1.05';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '>1.5.0';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::GREATER_THAN)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testPatchWithPadding()
    {
        $inStr = '<=1.05.03';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '<=1.5.3';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::LESS_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }


    public function testWithWhitespace()
    {
        $inStr = '  >= 1.5.3 ';

        $outRangedVersion = VersionEngine::parseRangedVersion($inStr);

        $expectStr = '>=1.5.3';
        $expectRangedVersion = new RangedVersion();
        $expectRangedVersion
            ->setComparator(RangedVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectRangedVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3)
            ->setLabel(null)
            ->setLabelNumber(0)
            ->setLabelPrecedence(Version::LABEL_NONE);

        $this->assertSame($expectStr, $outRangedVersion->__toString());
        $this->assertEquals($expectRangedVersion, $outRangedVersion);
    }
}
