<?php

/**
 * Tests to ensure correct handling of version number equality version.
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

namespace ptlis\SemanticVersion\Test\Version\Comparator;

use ptlis\SemanticVersion\Version\Comparator\EqualTo;
use ptlis\SemanticVersion\Label\LabelAlpha;
use ptlis\SemanticVersion\Label\LabelBeta;
use ptlis\SemanticVersion\Version\Version;

/**
 * Tests to ensure correct handling of version number equality version.
 */
class CompareVersionEqualToTest extends \PHPUnit_Framework_TestCase
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

        $equalTo = new EqualTo();

        $this->assertTrue($equalTo->compare($version1, $version2));
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

        $equalTo = new EqualTo();

        $this->assertTrue($equalTo->compare($version1, $version2));
    }


    public function testEqualNumberLabelMetadata()
    {
        $version1 = new Version();
        $version1
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAlpha(1, 'r501'));

        $version2 = new Version();
        $version2
            ->setMajor(1)
            ->setMinor(0)
            ->setPatch(0)
            ->setLabel(new LabelAlpha(1, 'r501'));

        $equalTo = new EqualTo();

        $this->assertTrue($equalTo->compare($version1, $version2));
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

        $equalTo = new EqualTo();

        $this->assertFalse($equalTo->compare($version1, $version2));
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

        $equalTo = new EqualTo();

        $this->assertFalse($equalTo->compare($version1, $version2));
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

        $equalTo = new EqualTo();

        $this->assertFalse($equalTo->compare($version1, $version2));
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

        $equalTo = new EqualTo();

        $this->assertFalse($equalTo->compare($version1, $version2));
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

        $equalTo = new EqualTo();

        $this->assertFalse($equalTo->compare($version1, $version2));
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

        $equalTo = new EqualTo();

        $this->assertFalse($equalTo->compare($version1, $version2));
    }
}
