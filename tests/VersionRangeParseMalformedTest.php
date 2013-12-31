<?php

/**
 * Tests to ensure correct parsing of malformed version ranges.
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

/*
 * Tests to ensure correct parsing of malformed version ranges.
 */
class VersionRangeParseMalformedTest extends \PHPUnit_Framework_TestCase
{
    public function testTrailingMajorDot()
    {
        $inRange = '>=1.<=1.2.0';
        $expectRange = '>=1.0.0<=1.2.0';
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

        $this->assertSame('<=', $outRange->getUpper()->getComparator());
        $this->assertSame(1, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(2, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testTrailingMinorDot()
    {
        $inRange = '>=1.0.<=1.2.0';
        $expectRange = '>=1.0.0<=1.2.0';
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

        $this->assertSame('<=', $outRange->getUpper()->getComparator());
        $this->assertSame(1, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(2, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testTrailingLabelHyphen()
    {
        $inRange = '>=1.0.0-<=1.2.0';
        $expectRange = '>=1.0.0<=1.2.0';
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

        $this->assertSame('<=', $outRange->getUpper()->getComparator());
        $this->assertSame(1, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(2, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }


    public function testOmittedLabelNumberDot()
    {
        $inRange = '>=1.0.0-rc1<=1.2.0';
        $expectRange = '>=1.0.0-rc1<=1.2.0';
        $outRange = VersionEngine::parseVersionRange($inRange);
        $valid = VersionEngine::validVersionRange($inRange);

        $this->assertNotNull($outRange);
        $this->assertSame($expectRange, $outRange->__toString());
        $this->assertTrue($valid);

        $this->assertSame('>=', $outRange->getLower()->getComparator());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getMajor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getLower()->getVersion()->getPatch());
        $this->assertSame('rc1', $outRange->getLower()->getVersion()->getLabel());
        $this->assertSame(1, $outRange->getLower()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_RC, $outRange->getLower()->getVersion()->getLabelPrecedence());

        $this->assertSame('<=', $outRange->getUpper()->getComparator());
        $this->assertSame(1, $outRange->getUpper()->getVersion()->getMajor());
        $this->assertSame(2, $outRange->getUpper()->getVersion()->getMinor());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getPatch());
        $this->assertSame(null, $outRange->getUpper()->getVersion()->getLabel());
        $this->assertSame(0, $outRange->getUpper()->getVersion()->getLabelNumber());
        $this->assertSame(Version::LABEL_NONE, $outRange->getUpper()->getVersion()->getLabelPrecedence());
    }
}
