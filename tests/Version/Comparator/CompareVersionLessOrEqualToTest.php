<?php

/**
 * Tests to ensure correct handling of version number less or equal to version.
 *
 * PHP Version 5.3
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

namespace tests\Version\Comparator;

use ptlis\SemanticVersion\Version\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Label\LabelAbsent;
use ptlis\SemanticVersion\Label\LabelRc;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of version number less than version.
 */
class CompareVersionLessOrEqualToTest extends \PHPUnit_Framework_TestCase
{
    public function testGreaterThanMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(2);

        $version2 = new Version();
        $version2
            ->setMajor(1);

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertFalse($lessOrEqualTo->compare($version1, $version2));
    }


    public function testLessThanMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1);

        $version2 = new Version();
        $version2
            ->setMajor(2);

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($version1, $version2));
    }


    public function testEqualToMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1);

        $version2 = new Version();
        $version2
            ->setMajor(1);

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($version1, $version2));
    }


    public function testGreaterThanMinor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(5);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(3);

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertFalse($lessOrEqualTo->compare($version1, $version2));
    }


    public function testLessThanMinor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(3);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(5);

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($version1, $version2));
    }


    public function testEqualToMinor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(3);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(3);

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($version1, $version2));
    }


    public function testGreaterThanPatch()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(7);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3);

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertFalse($lessOrEqualTo->compare($version1, $version2));
    }


    public function testLessThanPatch()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(3);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(7);

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($version1, $version2));
    }


    public function testEqualToPatch()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(7);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(5)
            ->setPatch(7);

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($version1, $version2));
    }


    public function testGreaterThanLabelAlphaBeta()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelBeta());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAlpha());

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertFalse($lessOrEqualTo->compare($version1, $version2));
    }


    public function testLessThanLabelAlphaBeta()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAlpha());

        $version2 = new Version(new LabelAlpha());
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelBeta());

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($version1, $version2));
    }


    public function testGreaterThanLabelBetaRc()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelBeta());

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertFalse($lessOrEqualTo->compare($version1, $version2));
    }


    public function testLessThanLabelBetaRc()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelBeta());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc());

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($version1, $version2));
    }


    public function testGreaterThanLabelRcAbsent()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc());

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertFalse($lessOrEqualTo->compare($version1, $version2));
    }


    public function testLessThanLabelRcAbsent()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($version1, $version2));
    }


    public function testEqualToLabelAbsent()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAbsent());

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($version1, $version2));
    }


    public function testGreaterThanNumberedLabel()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc(2));

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc());

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertFalse($lessOrEqualTo->compare($version1, $version2));
    }


    public function testLessThanNumberedLabel()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc(2));

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($version1, $version2));
    }


    public function testEqualToNumberedLabel()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc(2));

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc(2));

        $lessOrEqualTo = new LessOrEqualTo();

        $this->assertTrue($lessOrEqualTo->compare($version1, $version2));
    }
}
