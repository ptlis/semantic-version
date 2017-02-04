<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test\Version;

use PHPUnit\Framework\TestCase;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\Version\VersionBuilder;
use system\L;

class VersionBuilderTest extends TestCase
{
    /**
     * @covers \ptlis\SemanticVersion\Version\VersionBuilder
     */
    public function testBuildMajorOnly()
    {
        $tokenList = [new Token(Token::DIGITS, 5)];
        $labelList = [];

        $version = (new VersionBuilder(new LabelBuilder()))
            ->buildFromTokens($tokenList, $labelList);

        $this->assertEquals(new Version(5), $version);
    }

    /**
     * @covers \ptlis\SemanticVersion\Version\VersionBuilder
     */
    public function testBuildMajorMinor()
    {
        $tokenList = [
            new Token(Token::DIGITS, 5),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 3)
        ];
        $labelList = [];

        $version = (new VersionBuilder(new LabelBuilder()))
            ->buildFromTokens($tokenList, $labelList);

        $this->assertEquals(new Version(5, 3), $version);
    }

    /**
     * @covers \ptlis\SemanticVersion\Version\VersionBuilder
     */
    public function testBuildMajorMinorPatch()
    {
        $tokenList = [
            new Token(Token::DIGITS, 5),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 3),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 4)
        ];
        $labelList = [];

        $version = (new VersionBuilder(new LabelBuilder()))
            ->buildFromTokens($tokenList, $labelList);

        $this->assertEquals(new Version(5, 3, 4), $version);
    }

    /**
     * @covers \ptlis\SemanticVersion\Version\VersionBuilder
     */
    public function testBuildMajorMinorPatchLabel()
    {
        $tokenList = [
            new Token(Token::DIGITS, 5),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 3),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 4)
        ];
        $labelList = [
            new Token(Token::LABEL_STRING, 'alpha'),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 1)
        ];

        $version = (new VersionBuilder(new LabelBuilder()))
            ->buildFromTokens($tokenList, $labelList);

        $this->assertEquals(new Version(5, 3, 4, new Label(Label::PRECEDENCE_ALPHA, 1, 'alpha')), $version);
    }

    /**
     * @covers \ptlis\SemanticVersion\Version\VersionBuilder
     */
    public function testBuildInvalid()
    {
        $this->expectException('\RuntimeException');

        $tokenList = [
            new Token(Token::DIGITS, 5),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 3),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 4),
            new Token(Token::DOT_SEPARATOR, '.')
        ];
        $labelList = [];

        (new VersionBuilder(new LabelBuilder()))
            ->buildFromTokens($tokenList, $labelList);
    }
}