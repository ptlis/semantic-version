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

use ptlis\SemanticVersion\Entity\ComparatorVersion;
use ptlis\SemanticVersion\Entity\Label\LabelNone;
use ptlis\SemanticVersion\Entity\Version;
use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct parsing of valid version ranges.
 */
class ParseComparatorVersionValidTest extends \PHPUnit_Framework_TestCase
{
    public function testAllFields()
    {
        $inStr = '>1.0.0';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '>1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_THAN)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);

        $this->assertEquals($expectComparatorVersion->getComparator(), $outComparatorVersion->getComparator());
        $this->assertEquals($expectComparatorVersion->getVersion(), $outComparatorVersion->getVersion());
    }

    public function testGreaterThanMajor()
    {
        $inStr = '>1';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '>1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_THAN)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testGreaterThanMajorMinor()
    {
        $inStr = '>1.7';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '>1.7.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_THAN)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testGreaterThanMajorMinorPatch()
    {
        $inStr = '>1.7.9';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '>1.7.9';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_THAN)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testGreaterOrEqualToMajor()
    {
        $inStr = '>=1';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '>=1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testGreaterOrEqualToMajorMinor()
    {
        $inStr = '>=1.7';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '>=1.7.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testGreaterOrEqualToMajorMinorPatch()
    {
        $inStr = '>=1.7.9';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '>=1.7.9';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testLessThanMajor()
    {
        $inStr = '<1';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '<1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::LESS_THAN)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testLessThanMajorMinor()
    {
        $inStr = '<1.7';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '<1.7.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::LESS_THAN)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testLessThanMajorMinorPatch()
    {
        $inStr = '<1.7.9';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '<1.7.9';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::LESS_THAN)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testLessOrEqualToMajor()
    {
        $inStr = '<=1';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '<=1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::LESS_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testLessOrEqualToMajorMinor()
    {
        $inStr = '<=1.7';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '<=1.7.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::LESS_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testLessOrEqualToMajorMinorPatch()
    {
        $inStr = '<=1.7.9';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '<=1.7.9';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::LESS_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testEqualToMajor()
    {
        $inStr = '=1';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '=1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testEqualToMajorMinor()
    {
        $inStr = '=1.7';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '=1.7.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testEqualToMajorMinorPatch()
    {
        $inStr = '=1.7.9';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '=1.7.9';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }

    // TODO: labels
    // TODO: numbered labels






    public function testMajorWithPadding()
    {
        $inStr = '<01';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '<1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::LESS_THAN)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testMinorWithPadding()
    {
        $inStr = '>1.05';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '>1.5.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_THAN)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testPatchWithPadding()
    {
        $inStr = '<=1.05.03';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '<=1.5.3';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::LESS_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testWithWhitespace()
    {
        $inStr = '  >= 1.5.3 ';

        $outComparatorVersion = VersionEngine::parseComparatorVersion($inStr);

        $expectStr = '>=1.5.3';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(ComparatorVersion::GREATER_OR_EQUAL_TO)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3)
            ->setLabel(new LabelNone());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }
}
