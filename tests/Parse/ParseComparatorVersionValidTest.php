<?php

/**
 * Tests to ensure correct parsing of valid bounding pairs.
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

use ptlis\SemanticVersion\Comparator\EqualTo;
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\GreaterThan;
use ptlis\SemanticVersion\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Label\LabelDev;
use ptlis\SemanticVersion\Label\LabelAbsent;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct parsing of valid bounding pairs.
 */
class ParseComparatorVersionValidTest extends \PHPUnit_Framework_TestCase
{
    public function testAllFields()
    {
        $inStr = '>1.0.0';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterThan())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);

        $this->assertEquals($expectComparatorVersion->getComparator(), $outComparatorVersion->getComparator());
        $this->assertEquals($expectComparatorVersion->getVersion(), $outComparatorVersion->getVersion());
    }

    public function testGreaterThanMajor()
    {
        $inStr = '>1';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterThan())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testGreaterThanMajorMinor()
    {
        $inStr = '>1.7';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>1.7.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterThan())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testGreaterThanMajorMinorPatch()
    {
        $inStr = '>1.7.9';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>1.7.9';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterThan())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testGreaterOrEqualToMajor()
    {
        $inStr = '>=1';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>=1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testGreaterOrEqualToMajorMinor()
    {
        $inStr = '>=1.7';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>=1.7.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testGreaterOrEqualToMajorMinorPatch()
    {
        $inStr = '>=1.7.9';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>=1.7.9';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testLessThanMajor()
    {
        $inStr = '<1';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '<1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new LessThan())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testLessThanMajorMinor()
    {
        $inStr = '<1.7';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '<1.7.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new LessThan())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testLessThanMajorMinorPatch()
    {
        $inStr = '<1.7.9';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '<1.7.9';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new LessThan)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testLessOrEqualToMajor()
    {
        $inStr = '<=1';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '<=1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new LessOrEqualTo())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testLessOrEqualToMajorMinor()
    {
        $inStr = '<=1.7';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '<=1.7.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new LessOrEqualTo)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testLessOrEqualToMajorMinorPatch()
    {
        $inStr = '<=1.7.9';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '<=1.7.9';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new LessOrEqualTo)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testEqualToMajor()
    {
        $inStr = '=1';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '=1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new EqualTo())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testEqualToMajorMinor()
    {
        $inStr = '=1.7';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '=1.7.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new EqualTo())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testEqualToMajorMinorPatch()
    {
        $inStr = '=1.7.9';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '=1.7.9';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new EqualTo())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(7)
            ->setPatch(9)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }

    // TODO: labels
    // TODO: numbered labels






    public function testMajorWithPadding()
    {
        $inStr = '<01';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '<1.0.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new LessThan)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testMinorWithPadding()
    {
        $inStr = '>1.05';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>1.5.0';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterThan())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testPatchWithPadding()
    {
        $inStr = '<=1.05.03';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '<=1.5.3';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new LessOrEqualTo)
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testWithWhitespace()
    {
        $inStr = '  >= 1.5.3 ';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>=1.5.3';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion(new Version());
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3)
            ->setLabel(new LabelAbsent());

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }


    public function testWithMetadata()
    {
        $inStr = '>=1.5.3-dev.3+1.75';

        $engine = new VersionEngine();
        $outComparatorVersion = $engine->parseComparatorVersion($inStr);

        $expectStr = '>=1.5.3-dev.3+1.75';
        $expectComparatorVersion = new ComparatorVersion();
        $expectComparatorVersion
            ->setComparator(new GreaterOrEqualTo())
            ->setVersion(new Version());
        $expectLabel = new LabelDev();
        $expectLabel
            ->setName('dev')
            ->setVersion(3)
            ->setBuildMetaData('1.75');
        $expectComparatorVersion->getVersion()
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3)
            ->setLabel($expectLabel);

        $this->assertSame($expectStr, $outComparatorVersion->__toString());
        $this->assertEquals($expectComparatorVersion, $outComparatorVersion);
    }
}
