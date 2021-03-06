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
use ptlis\SemanticVersion\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Comparator\EqualTo;
use ptlis\SemanticVersion\Comparator\GreaterThan;
use ptlis\SemanticVersion\Parse\RangeParser\ComparatorVersionParser;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;

/**
 * @covers \ptlis\SemanticVersion\Parse\RangeParser\ComparatorVersionParser
 */
final class ComparatorVersionParserTest extends TestCase
{
    public function testValidComparatorVersionImplicitEqual()
    {
        $parser = new ComparatorVersionParser(
            new ComparatorFactory(),
            new VersionParser(new LabelBuilder())
        );

        $tokenList = [
            new Token(Token::DIGITS, 1)
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(
            new ComparatorVersion(
                new EqualTo(),
                new Version(1, 0, 0)
            ),
            $parser->parse($tokenList)
        );
    }

    public function testValidComparatorVersionMajorOnly()
    {
        $parser = new ComparatorVersionParser(
            new ComparatorFactory(),
            new VersionParser(new LabelBuilder())
        );

        $tokenList = [
            new Token(Token::GREATER_THAN, '>'),
            new Token(Token::DIGITS, 1)
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(
            new ComparatorVersion(
                new GreaterThan(),
                new Version(1, 0, 0)
            ),
            $parser->parse($tokenList)
        );
    }

    public function testValidComparatorVersionMajorMinor()
    {
        $parser = new ComparatorVersionParser(
            new ComparatorFactory(),
            new VersionParser(new LabelBuilder())
        );

        $tokenList = [
            new Token(Token::GREATER_THAN, '>'),
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 5)
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(
            new ComparatorVersion(
                new GreaterThan(),
                new Version(1, 5, 0)
            ),
            $parser->parse($tokenList)
        );
    }

    public function testValidComparatorVersionMajorMinorPatch()
    {
        $parser = new ComparatorVersionParser(
            new ComparatorFactory(),
            new VersionParser(new LabelBuilder())
        );

        $tokenList = [
            new Token(Token::GREATER_THAN, '>'),
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 5),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 2)
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(
            new ComparatorVersion(
                new GreaterThan(),
                new Version(1, 5, 2)
            ),
            $parser->parse($tokenList)
        );
    }

    public function testValidComparatorVersionMajorMinorPatchLabel()
    {
        $parser = new ComparatorVersionParser(
            new ComparatorFactory(),
            new VersionParser(new LabelBuilder())
        );

        $tokenList = [
            new Token(Token::GREATER_THAN, '>'),
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 5),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 2),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::LABEL_STRING, 'alpha'),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 1)
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(
            new ComparatorVersion(
                new GreaterThan(),
                new Version(1, 5, 2, new Label(Label::PRECEDENCE_ALPHA, 1))
            ),
            $parser->parse($tokenList)
        );
    }

    public function testNotComparatorVersion()
    {
        $parser = new ComparatorVersionParser(
            new ComparatorFactory(),
            new VersionParser(new LabelBuilder())
        );

        $tokenList = [
            new Token(Token::CARET_RANGE, '^'),
            new Token(Token::DIGITS, 2),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 2),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 5)
        ];

        $this->assertFalse($parser->canParse($tokenList));
    }

    public function testLabelOnlyError()
    {
        $this->setExpectedException('\RuntimeException');

        $parser = new ComparatorVersionParser(
            new ComparatorFactory(),
            new VersionParser(new LabelBuilder())
        );

        $tokenList = [
            new Token(Token::LABEL_STRING, 'bob')
        ];

        $this->assertFalse($parser->canParse($tokenList));
        $parser->parse($tokenList);
    }
}
