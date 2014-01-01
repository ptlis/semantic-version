<?php

/**
 * Tests to ensure correct handling of version number equality comparison.
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

use ptlis\SemanticVersion\Entity\Label\LabelAlpha;
use ptlis\SemanticVersion\Entity\Label\LabelBeta;
use ptlis\SemanticVersion\Entity\Version;

/**
 * Tests to ensure correct handling of version number equality comparison.
 */
class CompareVersionTest extends \PHPUnit_Framework_TestCase
{
    public function testEqualNumberOnly()
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
            ->setPatch(0);

        $this->assertTrue($version1->equalTo($version2));
    }


    public function testEqualNumberLabel()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAlpha(1));

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAlpha(1));

        $this->assertTrue($version1->equalTo($version2));
    }


    public function testNotEqualDifferentMajor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);

        $version2 = new Version();
        $version2
            ->setMajor(2)
            ->setMinor(0)
            ->setPatch(0);

        $this->assertFalse($version1->equalTo($version2));
    }


    public function testNotEqualDifferentMinor()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0);

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(1)
            ->setPatch(0);

        $this->assertFalse($version1->equalTo($version2));
    }


    public function testNotEqualDifferentPatch()
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
            ->setPatch(5);

        $this->assertFalse($version1->equalTo($version2));
    }


    public function testNotEqualDifferentOmitLabel()
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
            ->setPatch(0)
            ->setLabel(new LabelAlpha());

        $this->assertFalse($version1->equalTo($version2));
    }


    public function testNotEqualDifferentLabelNumber()
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
            ->setLabel(new LabelAlpha(1));

        $this->assertFalse($version1->equalTo($version2));
    }


    public function testNotEqualDifferentLabelName()
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

        $this->assertFalse($version1->equalTo($version2));
    }
}
