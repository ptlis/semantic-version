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

use PHPUnit\Framework\TestCase;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\Version;

/**
 * @covers \ptlis\SemanticVersion\Parse\VersionParser
 */
class VersionParserTest extends TestCase
{
    public function testMajorOnly()
    {
        $matcher = new VersionParser(new LabelBuilder());

        $tokenList = [
            new Token(Token::DIGITS, '5')
        ];

        $this->assertEquals(
            new Version(5, 0, 0),
            $matcher->parse($tokenList)
        );
    }

    public function testMajorOnlyTrailingDot()
    {
        $matcher = new VersionParser(new LabelBuilder());

        $tokenList = [
            new Token(Token::DIGITS, '2'),
            new Token(Token::DOT_SEPARATOR, '.')
        ];

        $this->assertEquals(
            new Version(2, 0, 0),
            $matcher->parse($tokenList)
        );
    }

    public function testMajorMinor()
    {
        $matcher = new VersionParser(new LabelBuilder());

        $tokenList = [
            new Token(Token::DIGITS, '5'),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, '3')
        ];

        $this->assertEquals(
            new Version(5, 3, 0),
            $matcher->parse($tokenList)
        );
    }

    public function testMajorMinorTrailingDot()
    {
        $matcher = new VersionParser(new LabelBuilder());

        $tokenList = [
            new Token(Token::DIGITS, '5'),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, '3'),
            new Token(Token::DOT_SEPARATOR, '.')
        ];

        $this->assertEquals(
            new Version(5, 3, 0),
            $matcher->parse($tokenList)
        );
    }

    public function testMajorMinorPatch()
    {
        $matcher = new VersionParser(new LabelBuilder());

        $tokenList = [
            new Token(Token::DIGITS, '2'),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, '9'),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, '5')
        ];

        $this->assertEquals(
            new Version(2, 9, 5),
            $matcher->parse($tokenList)
        );
    }

    public function testMajorMinorPatchLabel()
    {
        $matcher = new VersionParser(new LabelBuilder());

        $tokenList = [
            new Token(Token::DIGITS, '2'),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, '9'),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, '5'),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::LABEL_STRING, 'alpha')
        ];

        $this->assertEquals(
            new Version(2, 9, 5, new Label(Label::PRECEDENCE_ALPHA, null, 'alpha')),
            $matcher->parse($tokenList)
        );
    }

    public function testMajorMinorPatchLabelVersion()
    {
        $matcher = new VersionParser(new LabelBuilder());

        $tokenList = [
            new Token(Token::DIGITS, '2'),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, '9'),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, '5'),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::LABEL_STRING, 'alpha'),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, '1')
        ];

        $this->assertEquals(
            new Version(2, 9, 5, new Label(Label::PRECEDENCE_ALPHA, 1, 'alpha')),
            $matcher->parse($tokenList)
        );
    }

    public function testInvalidLabelOnly()
    {
        $this->expectException('\RuntimeException');

        $matcher = new VersionParser(new LabelBuilder());

        $tokenList = [
            new Token(Token::LABEL_STRING, 'alpha')
        ];

        $matcher->parse($tokenList);
    }
}