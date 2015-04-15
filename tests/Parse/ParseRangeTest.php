<?php

/**
 * PHP Version 5.3
 *
 * @copyright   (c) 2014-2015 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test\Parse;

use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;

class ParseRangeTest extends TestDataProvider
{
    /**
     * @dataProvider tokenProvider
     */
    public function testParseRange($version, $tokenList, $expectedRange, $expectedSerialization)
    {
        $parser = new VersionParser(new LabelBuilder());

        $range = $parser->parseRange($tokenList);

        $this->assertEquals(
            $expectedRange,
            $range
        );

        $this->assertEquals(
            $expectedSerialization,
            strval($range)
        );
    }

    /**
     * @dataProvider tokenProvider
     */
    public function testSerializeRange($version, $tokenList, $expectedRange, $expectedSerialization)
    {
        $this->assertEquals(
            $expectedSerialization,
            strval($expectedRange)
        );
    }
}
