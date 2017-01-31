<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test\Parse;

use ptlis\SemanticVersion\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Parse\Matcher\BranchParser;
use ptlis\SemanticVersion\Parse\Matcher\CaretRangeParser;
use ptlis\SemanticVersion\Parse\Matcher\ComparatorVersionParser;
use ptlis\SemanticVersion\Parse\Matcher\HyphenatedRangeParser;
use ptlis\SemanticVersion\Parse\Matcher\TildeRangeParser;
use ptlis\SemanticVersion\Parse\Matcher\WildcardRangeParser;
use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\VersionBuilder;

final class ParseRangeTest extends TestDataProvider
{
    private function getMatcherList()
    {
        $comparatorFactory = new ComparatorFactory();
        $versionBuilder = new VersionBuilder(new LabelBuilder());

        $wildcardParser = new WildcardRangeParser(
            $comparatorFactory->get('>='),
            $comparatorFactory->get('<')
        );

        return [
            new CaretRangeParser(
                $comparatorFactory->get('>='),
                $comparatorFactory->get('<')
            ),
            new TildeRangeParser($wildcardParser),
            $wildcardParser,
            new BranchParser($wildcardParser),
            new ComparatorVersionParser(
                $comparatorFactory,
                $versionBuilder
            ),
            new HyphenatedRangeParser(
                $versionBuilder,
                $comparatorFactory->get('>='),
                $comparatorFactory->get('<'),
                $comparatorFactory->get('<=')
            )
        ];
    }

    /**
     * @dataProvider tokenProvider
     * @covers \ptlis\SemanticVersion\Parse\VersionParser
     */
    public function testParseRange($version, $tokenList, $expectedRange, $expectedSerialization)
    {
        $parser = new VersionParser($this->getMatcherList());
        $range = $parser->parseRange($tokenList);

        $this->assertEquals($expectedRange, $range);
        $this->assertEquals($expectedSerialization, strval($range));
    }
}
