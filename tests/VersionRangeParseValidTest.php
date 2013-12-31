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

class VersionRangeParseValidTest extends \PHPUnit_Framework_TestCase
{
    public function testBothMajorFullyQualified()
    {
        $inRange = '>1.0.0<=2.0.0';
        $expectRange = '>1.0.0<=2.0.0';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame('<=', $outRange->getUpper()->getComparator());
        $this->assertSame(2, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testBothMajorMinorFullyQualified()
    {
        $inRange = '>1.0.0<=1.1.0';
        $expectRange = '>1.0.0<=1.1.0';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame('<=', $outRange->getUpper()->getComparator());
        $this->assertSame(1, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(1, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testBothMajorMinorPatchFullyQualified()
    {
        $inRange = '>1.0.0<=1.0.5';
        $expectRange = '>1.0.0<=1.0.5';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame('<=', $outRange->getUpper()->getComparator());
        $this->assertSame(1, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(5, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testLowerMajorFullyQualified()
    {
        $inRange = '>1.0.0';
        $expectRange = '>1.0.0';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame(null, $outRange->getUpper());
    }


    public function testUpperMajorFullyQualified()
    {
        $inRange = '<=1.0.0';
        $expectRange = '<=1.0.0';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame(null, $outRange->getLower());

        $this->assertSame('<=', $outRange->getUpper()->getComparator());
        $this->assertSame(1, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testSingleMajorPartial()
    {
        $inRange = '1';
        $expectRange = '>=1.0.0<2.0.0';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>=', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame('<', $outRange->getUpper()->getComparator());
        $this->assertSame(2, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testSingleMajorMinorPartial()
    {
        $inRange = '1.0';
        $expectRange = '>=1.0.0<1.1.0';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>=', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame('<', $outRange->getUpper()->getComparator());
        $this->assertSame(1, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(1, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testSingleMajorMinorPatch()
    {
        $inRange = '1.0.5';
        $expectRange = '=1.0.5';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('=', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(5, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame('=', $outRange->getUpper()->getComparator());
        $this->assertSame(1, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(5, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testSingleMajorMinorPatchLabel()
    {
        $inRange = '1.0.5-rc2';
        $expectRange = '=1.0.5-rc2';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('=', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(5, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame('rc2', $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(2, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_RC, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame('=', $outRange->getUpper()->getComparator());
        $this->assertSame(1, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(5, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame('rc2', $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(2, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_RC, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testTildeMajor()
    {
        $inRange = '~1';
        $expectRange = '>=1.0.0<2.0.0';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>=', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame('<', $outRange->getUpper()->getComparator());
        $this->assertSame(2, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testTildeMajorMinor()
    {
        $inRange = '~1.7';
        $expectRange = '>=1.7.0<2.0.0';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>=', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(7, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame('<', $outRange->getUpper()->getComparator());
        $this->assertSame(2, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testTildeMajorMinorPatch()
    {
        $inRange = '~1.7.9';
        $expectRange = '>=1.7.9<1.8.0';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>=', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(7, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(9, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame('<', $outRange->getUpper()->getComparator());
        $this->assertSame(1, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(8, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testTildeMajorWildcard()
    {
        $inRange = '~1.x';
        $expectRange = '>=1.0.0<2.0.0';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>=', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame('<', $outRange->getUpper()->getComparator());
        $this->assertSame(2, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testTildeMajorMinorWildcard()
    {
        $inRange = '~1.7.x';
        $expectRange = '>=1.7.0<2.0.0';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>=', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(7, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame('<', $outRange->getUpper()->getComparator());
        $this->assertSame(2, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }
}
