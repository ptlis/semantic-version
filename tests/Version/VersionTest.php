<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test\Version;

use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Version;

class VersionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \ptlis\SemanticVersion\Version\Version
     */
    public function testCreate()
    {
        $version = new Version(1, 0, 5, new Label(Label::PRECEDENCE_ALPHA, 1));

        $this->assertEquals(1, $version->getMajor());
        $this->assertEquals(0, $version->getMinor());
        $this->assertEquals(5, $version->getPatch());
        $this->assertEquals(new Label(Label::PRECEDENCE_ALPHA, 1), $version->getLabel());
        $this->assertEquals('1.0.5-alpha.1', strval($version));
    }

    /**
     * @covers \ptlis\SemanticVersion\Version\Version
     */
    public function testCreateWithDefaults()
    {
        $version = new Version(1);

        $this->assertEquals(1, $version->getMajor());
        $this->assertEquals(0, $version->getMinor());
        $this->assertEquals(0, $version->getPatch());
        $this->assertEquals(new Label(Label::PRECEDENCE_ABSENT), $version->getLabel());
    }

    /**
     * @covers \ptlis\SemanticVersion\Version\Version
     */
    public function testCreateAbsent()
    {
        $version = new Version(1, 0, 5);

        $this->assertEquals('1.0.5', strval($version));
    }

    /**
     * @covers \ptlis\SemanticVersion\Version\Version
     */
    public function testIsSatisfiedBy()
    {
        $version = new Version(1, 0, 5);

        $this->assertTrue($version->isSatisfiedBy(new Version(1, 0, 5)));
    }

    /**
     * @covers \ptlis\SemanticVersion\Version\Version
     */
    public function testIsNotSatisfiedBy()
    {
        $version = new Version(1, 0, 5);

        $this->assertFalse($version->isSatisfiedBy(new Version(1, 0, 6)));
    }
}
