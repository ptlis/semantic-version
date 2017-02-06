<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Parse\RangeMatcher;

use PHPUnit\Framework\TestCase;
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\Parse\RangeMatcher\TildeRangeParser;
use ptlis\SemanticVersion\Parse\RangeMatcher\WildcardRangeParser;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;

class TildeRangeParserTest extends TestCase
{
    /**
     * @covers \ptlis\SemanticVersion\Parse\RangeMatcher\TildeRangeParser
     */
    public function testVersionBranch()
    {
        $parser = new TildeRangeParser(new WildcardRangeParser(new GreaterOrEqualTo(), new LessThan()));

        $tokenList = [
            new Token(Token::TILDE_RANGE, '~'),
            new Token(Token::DIGITS, 2),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 2),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, '0')
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(
            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(2, 2, 0)
                ),
                new ComparatorVersion(
                    new LessThan(),
                    new Version(2, 3, 0)
                )
            ),
            $parser->parse($tokenList)
        );
    }

    /**
     * @covers \ptlis\SemanticVersion\Parse\RangeMatcher\TildeRangeParser
     */
    public function testNotVersionBranch()
    {
        $parser = new TildeRangeParser(new WildcardRangeParser(new GreaterOrEqualTo(), new LessThan()));

        $tokenList = [
            new Token(Token::DIGITS, 2),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 2),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 5)
        ];

        $this->assertFalse($parser->canParse($tokenList));
    }
}