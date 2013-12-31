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

use ptlis\SemanticVersion\Entity\Version;
use ptlis\SemanticVersion\VersionEngine;

/**
 * Tests to ensure correct parsing of valid version ranges.
 */
class RangedVersionParseValidTest extends \PHPUnit_Framework_TestCase
{
    public function testGreaterThanMajor()
    {
        $inRanged = '>1';
        $expectRanged = '>1.0.0';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(0, $outRanged->getVersion()->getMinor());
        $this->assertSame(0, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testGreaterThanMajorMinor()
    {
        $inRanged = '>1.7';
        $expectRanged = '>1.7.0';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(7, $outRanged->getVersion()->getMinor());
        $this->assertSame(0, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testGreaterThanMajorMinorPatch()
    {
        $inRanged = '>1.7.9';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($inRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(7, $outRanged->getVersion()->getMinor());
        $this->assertSame(9, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testGreaterOrEqualToMajor()
    {
        $inRanged = '>=1';
        $expectRanged = '>=1.0.0';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>=', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(0, $outRanged->getVersion()->getMinor());
        $this->assertSame(0, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testGreaterOrEqualToMajorMinor()
    {
        $inRanged = '>=1.7';
        $expectRanged = '>=1.7.0';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>=', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(7, $outRanged->getVersion()->getMinor());
        $this->assertSame(0, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testGreaterOrEqualToMajorMinorPatch()
    {
        $inRanged = '>=1.7.9';
        $expectRanged = '>=1.7.9';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>=', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(7, $outRanged->getVersion()->getMinor());
        $this->assertSame(9, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testLessThanMajor()
    {
        $inRanged = '<1';
        $expectRanged = '<1.0.0';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('<', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(0, $outRanged->getVersion()->getMinor());
        $this->assertSame(0, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testLessThanMajorMinor()
    {
        $inRanged = '<1.7';
        $expectRanged = '<1.7.0';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('<', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(7, $outRanged->getVersion()->getMinor());
        $this->assertSame(0, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testLessThanMajorMinorPatch()
    {
        $inRanged = '<1.7.9';
        $expectRanged = '<1.7.9';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('<', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(7, $outRanged->getVersion()->getMinor());
        $this->assertSame(9, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testLessOrEqualToMajor()
    {
        $inRanged = '<=1';
        $expectRanged = '<=1.0.0';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('<=', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(0, $outRanged->getVersion()->getMinor());
        $this->assertSame(0, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testLessOrEqualToMajorMinor()
    {
        $inRanged = '<=1.7';
        $expectRanged = '<=1.7.0';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('<=', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(7, $outRanged->getVersion()->getMinor());
        $this->assertSame(0, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testLessOrEqualToMajorMinorPatch()
    {
        $inRanged = '<=1.7.9';
        $expectRanged = '<=1.7.9';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('<=', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(7, $outRanged->getVersion()->getMinor());
        $this->assertSame(9, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testEqualToMajor()
    {
        $inRanged = '=1';
        $expectRanged = '=1.0.0';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('=', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(0, $outRanged->getVersion()->getMinor());
        $this->assertSame(0, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testEqualToMajorMinor()
    {
        $inRanged = '=1.7';
        $expectRanged = '=1.7.0';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('=', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(7, $outRanged->getVersion()->getMinor());
        $this->assertSame(0, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testEqualToMajorMinorPatch()
    {
        $inRanged = '=1.7.9';
        $expectRanged = '=1.7.9';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectRanged, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('=', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(7, $outRanged->getVersion()->getMinor());
        $this->assertSame(9, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }

    // TODO: labels
    // TODO: numbered labels






    public function testMajorWithPadding()
    {
        $inRanged = '<01';
        $expectVersion = '<1.0.0';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectVersion, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('<', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(0, $outRanged->getVersion()->getMinor());
        $this->assertSame(0, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testMinorWithPadding()
    {
        $inRanged = '>1.05';
        $expectVersion = '>1.5.0';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectVersion, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(5, $outRanged->getVersion()->getMinor());
        $this->assertSame(0, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testPatchWithPadding()
    {
        $inRanged = '<=1.05.03';
        $expectVersion = '<=1.5.3';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectVersion, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('<=', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(5, $outRanged->getVersion()->getMinor());
        $this->assertSame(3, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }


    public function testWithWhitespace()
    {
        $inRanged = '  >= 1.5.3 ';
        $expectVersion = '>=1.5.3';
        $outRanged = VersionEngine::parseRangedVersion($inRanged);
        $valid = VersionEngine::validRangedVersion($inRanged);

        $this->assertNotNull($outRanged);
        $this->assertSame($expectVersion, $outRanged->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>=', $outRanged->getComparator());
        $this->assertSame(1, $outRanged->getVersion()->getMajor());
        $this->assertSame(5, $outRanged->getVersion()->getMinor());
        $this->assertSame(3, $outRanged->getVersion()->getPatch());
        $this->assertSame(null, $outRanged->getVersion()->getLabel());
        $this->assertSame(0, $outRanged->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRanged->getVersion()->getLabelPrecedence());
    }
}
