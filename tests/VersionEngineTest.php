<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test;

use PHPUnit\Framework\TestCase;

use ptlis\SemanticVersion\Comparator\EqualTo;
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionEngine;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;

/**
 * @covers \ptlis\SemanticVersion\VersionEngine
 */
class VersionEngineTest extends TestCase
{
    public function testParseVersionSuccess()
    {
        $version = (new VersionEngine())->parseVersion('1.2.3-alpha.3');

        $this->assertEquals(
            new Version(1, 2, 3, new Label(Label::PRECEDENCE_ALPHA, 3, 'alpha')),
            $version
        );
    }

    public function testParseVersionErrorLabelOnly()
    {
        $this->setExpectedException('\InvalidArgumentException');

        (new VersionEngine())->parseVersion('bob');
    }

    public function testParseVersionErrorPassRange()
    {
        $this->setExpectedException('\InvalidArgumentException');

        (new VersionEngine())->parseVersion('1.5.2-2.8.1');
    }

    public function testParseVersionRangeSuccess()
    {
        $range = (new VersionEngine())->parseRange('1.5.2-2.8.1');

        $this->assertEquals(
            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(1, 5, 2)
                ),
                new ComparatorVersion(
                    new LessOrEqualTo(),
                    new Version(2, 8, 1)
                )
            ),
            $range
        );
    }

    public function testParseVersionRangePassVersion()
    {
        $range = (new VersionEngine())->parseRange('1.5.2');

        $this->assertEquals(
            new ComparatorVersion(
                new EqualTo(),
                new Version(1, 5, 2)
            ),
            $range
        );
    }

    public function testParseVersionRangePassComparatorVersion()
    {
        $range = (new VersionEngine())->parseRange('>=1.5.2');

        $this->assertEquals(
            new ComparatorVersion(
                new GreaterOrEqualTo(),
                new Version(1, 5, 2)
            ),
            $range
        );
    }

    public function testParseVersionRangeErrorLabelOnly()
    {
        $this->setExpectedException('\InvalidArgumentException');

        (new VersionEngine())->parseRange('foo');
    }
}
