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
use ptlis\SemanticVersion\Parse\RangeMatcher\BranchParser;
use ptlis\SemanticVersion\Parse\RangeMatcher\CaretRangeParser;
use ptlis\SemanticVersion\Parse\RangeMatcher\ComparatorVersionParser;
use ptlis\SemanticVersion\Parse\RangeMatcher\HyphenatedRangeParser;
use ptlis\SemanticVersion\Parse\RangeMatcher\TildeRangeParser;
use ptlis\SemanticVersion\Parse\RangeMatcher\WildcardRangeParser;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\Parse\VersionRangeParser;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;

final class VersionRangeParserTest extends TestDataProvider
{
    private function getMatcherList()
    {
        $comparatorFactory = new ComparatorFactory();
        $versionParser = new VersionParser(new LabelBuilder());

        $wildcardParser = new WildcardRangeParser(
            $comparatorFactory->get('>='),
            $comparatorFactory->get('<')
        );

        return [
            new CaretRangeParser(
                $versionParser,
                $comparatorFactory->get('>='),
                $comparatorFactory->get('<')
            ),
            new TildeRangeParser($wildcardParser),
            $wildcardParser,
            new BranchParser($wildcardParser),
            new ComparatorVersionParser(
                $comparatorFactory,
                $versionParser
            ),
            new HyphenatedRangeParser(
                $versionParser,
                $comparatorFactory->get('>='),
                $comparatorFactory->get('<'),
                $comparatorFactory->get('<=')
            )
        ];
    }

    /**
     * @dataProvider tokenProvider
     * @covers \ptlis\SemanticVersion\Parse\VersionRangeParser
     */
    public function testParseRange($version, $tokenList, $expectedRange, $expectedSerialization)
    {
        $parser = new VersionRangeParser($this->getMatcherList());
        $range = $parser->parseRange($tokenList);

        $this->assertEquals($expectedRange, $range);
        $this->assertEquals($expectedSerialization, strval($range));
    }

    /**
     * @covers \ptlis\SemanticVersion\Parse\VersionRangeParser
     */
    public function testParseRangeError()
    {
        $this->expectException('\RuntimeException');

        $parser = new VersionRangeParser($this->getMatcherList());
        $parser->parseRange([new Token(Token::LABEL_STRING, 'bob')]);
    }
}