<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test\Parse\RangeParser;

use PHPUnit\Framework\TestCase;
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\Parse\RangeParser\BranchParser;
use ptlis\SemanticVersion\Parse\RangeParser\WildcardRangeParser;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;

/**
 * @covers \ptlis\SemanticVersion\Parse\RangeParser\BranchParser
 * @covers \ptlis\SemanticVersion\Parse\RangeParser\ParseSimpleRange
 */
final class BranchParserTest extends TestCase
{
    public function testVersionBranch()
    {
        $parser = new BranchParser(
            new VersionParser(new LabelBuilder()), new GreaterOrEqualTo(), new LessThan(),
            new GreaterOrEqualTo(),
            new LessThan()
        );

        $tokenList = [
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 5),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::WILDCARD_DIGITS, '*'),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::LABEL_STRING, 'my_branch')
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(
            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(1, 5, 0)
                ),
                new ComparatorVersion(
                    new LessThan(),
                    new Version(1, 6, 0)
                )
            ),
            $parser->parse($tokenList)
        );
    }

    public function testNotVersionBranch()
    {
        $this->setExpectedException('\RuntimeException');

        $parser = new BranchParser(
            new VersionParser(new LabelBuilder()), new GreaterOrEqualTo(), new LessThan(),
            new GreaterOrEqualTo(),
            new LessThan()
        );

        $tokenList = [
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 5),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 3)
        ];

        $this->assertFalse($parser->canParse($tokenList));
        $parser->parse($tokenList);
    }
}
