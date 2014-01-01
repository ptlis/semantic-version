<?php

/**
 * Tests to ensure correct handling of version number greater than version.
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

use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Label\LabelNone;
use ptlis\SemanticVersion\Label\LabelRc;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of version number greater than version.
 */
class CompareVersionGreaterThanTest extends \PHPUnit_Framework_TestCase
{
    public function testGreaterThanMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(2);

        $version2 = new Version();
        $version2
            ->setMajor(1);

        $this->assertTrue($version1->greaterThan($version2));
    }


    public function testLessThanMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1);

        $version2 = new Version();
        $version2
            ->setMajor(2);

        $this->assertFalse($version1->greaterThan($version2));
    }


    public function testEqualToMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1);

        $version2 = new Version();
        $version2
            ->setMajor(1);

        $this->assertFalse($version1->greaterThan($version2));
    }


    public function testGreaterThanMinor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(1);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0);

        $this->assertTrue($version1->greaterThan($version2));
    }


    public function testLessThanMinor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(1);

        $this->assertFalse($version1->greaterThan($version2));
    }


    public function testEqualToMinor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0);

        $this->assertFalse($version1->greaterThan($version2));
    }


    public function testGreaterThanPatch()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(1);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);

        $this->assertTrue($version1->greaterThan($version2));
    }


    public function testLessThanPatch()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(1);

        $this->assertFalse($version1->greaterThan($version2));
    }


    public function testEqualToPatch()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(1);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(1);

        $this->assertFalse($version1->greaterThan($version2));
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

        $this->assertTrue($version1->greaterThan($version2));
    }


    public function testLessThanLabelAlphaBeta()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAlpha());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelBeta());

        $this->assertFalse($version1->greaterThan($version2));
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

        $this->assertTrue($version1->greaterThan($version2));
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

        $this->assertFalse($version1->greaterThan($version2));
    }


    public function testGreaterThanLabelRcNone()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelRc());

        $this->assertTrue($version1->greaterThan($version2));
    }


    public function testLessThanLabelRcNone()
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
            ->setLabel(new LabelNone());

        $this->assertFalse($version1->greaterThan($version2));
    }


    public function testEqualToLabel()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelNone());

        $this->assertFalse($version1->greaterThan($version2));
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

        $this->assertTrue($version1->greaterThan($version2));
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

        $this->assertFalse($version1->greaterThan($version2));
    }


    public function testEqualToNumberedLabel()
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
            ->setLabel(new LabelRc());

        $this->assertFalse($version1->greaterThan($version2));
    }
}
