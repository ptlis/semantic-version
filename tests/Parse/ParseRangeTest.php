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

use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;

class ParseRangeTest extends TestDataProvider
{
    /**
     * @dataProvider tokenProvider
     *
     * @param string $version
     * @param Token[] $tokenList
     * @param array $expectedValueList
     */
    public function testParseRange($version, $tokenList, $expectedValueList, $expectedSerialization)
    {
        $parser = new VersionParser(new LabelBuilder());

        $range = $parser->parseRange($tokenList);

        $this->assertEquals(
            $expectedValueList,
            $range
        );

        $this->assertEquals(
            $expectedSerialization,
            strval($range)
        );
    }
}
