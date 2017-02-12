<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test\Parse\RangeMatcher;

use PHPUnit\Framework\TestCase;
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\Parse\RangeMatcher\WildcardRangeParser;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;

final class WildcardRangeParserTest extends TestCase
{
    /**
     * @covers \ptlis\SemanticVersion\Parse\RangeMatcher\WildcardRangeParser
     */
    public function testValidWildcardRangePatch()
    {
        $parser = new WildcardRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan()
        );

        $tokenList = [
            new Token(Token::DIGITS, 5),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::WILDCARD_DIGITS, '*')
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(

            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(5, 1, 0)
                ),
                new ComparatorVersion(
                    new LessThan(),
                    new Version(5, 2, 0)
                )
            ),
            $parser->parse($tokenList)
        );
    }

    /**
     * @covers \ptlis\SemanticVersion\Parse\RangeMatcher\WildcardRangeParser
     */
    public function testValidWildcardRangeMinor()
    {
        $parser = new WildcardRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan()
        );

        $tokenList = [
            new Token(Token::DIGITS, 3),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::WILDCARD_DIGITS, '*')
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(
            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(3, 0, 0)
                ),
                new ComparatorVersion(
                    new LessThan(),
                    new Version(4, 0, 0)
                )
            ),
            $parser->parse($tokenList)
        );
    }

    /**
     * @covers \ptlis\SemanticVersion\Parse\RangeMatcher\WildcardRangeParser
     */
    public function testInvalidWildcardRangeMinor()
    {
        $this->expectException('\RuntimeException');

        $parser = new WildcardRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan()
        );

        $tokenList = [
            new Token(Token::DIGITS, 3),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 4),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 5)
        ];

        $this->assertFalse($parser->canParse($tokenList));
        $parser->parse($tokenList);
    }
}