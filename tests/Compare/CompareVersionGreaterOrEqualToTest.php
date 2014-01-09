<?php

/**
 * Tests to ensure correct handling of version number greater or equal to version.
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

namespace tests\Compare;

use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Label\LabelAbsent;
use ptlis\SemanticVersion\Label\LabelRc;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of version number greater than version.
 */
class CompareVersionGreaterOrEqualToTest extends \PHPUnit_Framework_TestCase
{
    public function testGreaterThanMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(2);

        $version2 = new Version();
        $version2
            ->setMajor(1);

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($version1, $version2));
    }


    public function testLessThanMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1);

        $version2 = new Version();
        $version2
            ->setMajor(2);

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertFalse($greaterOrEqualTo->compare($version1, $version2));
    }


    public function testEqualToMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1);

        $version2 = new Version();
        $version2
            ->setMajor(1);

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertFalse($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertFalse($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertFalse($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertFalse($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertFalse($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertFalse($greaterOrEqualTo->compare($version1, $version2));
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

        $greaterOrEqualTo = new GreaterOrEqualTo();

        $this->assertTrue($greaterOrEqualTo->compare($version1, $version2));
    }
}
