<?php

namespace ptlis\SemanticVersion\Test\Parse\RangeParser;

use PHPUnit\Framework\TestCase;
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\Parse\RangeParser\HyphenatedRangeParser;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;

/**
 * @covers \ptlis\SemanticVersion\Parse\RangeParser\HyphenatedRangeParser
 * @covers \ptlis\SemanticVersion\Parse\ChunkBySeparator
 */
final class HyphenatedRangeParserTest extends TestCase
{
    public function testValidHyphenatedRangeMajorOnly()
    {
        $parser = new HyphenatedRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan(),
            new LessOrEqualTo()
        );

        $tokenList = [
            new Token(Token::DIGITS, 1),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 2)
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(
            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(1, 0, 0)
                ),
                new ComparatorVersion(
                    new LessThan(),
                    new Version(3, 0, 0)
                )
            ),
            $parser->parse($tokenList)
        );
    }

    public function testValidHyphenatedRangeMajorMinor()
    {
        $parser = new HyphenatedRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan(),
            new LessOrEqualTo()
        );

        $tokenList = [
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 5),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 2),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 0)
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
                    new Version(2, 1, 0)
                )
            ),
            $parser->parse($tokenList)
        );
    }

    public function testValidHyphenatedRangeMajorMinorPatch()
    {
        $parser = new HyphenatedRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan(),
            new LessOrEqualTo()
        );

        $tokenList = [
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 3),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 7),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 2),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 5)
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(
            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(1, 3, 7)
                ),
                new ComparatorVersion(
                    new LessOrEqualTo(),
                    new Version(2, 1, 5)
                )
            ),
            $parser->parse($tokenList)
        );
    }

    public function testValidHyphenatedRangeMajorMinorPatchLabelOnFirst()
    {
        $parser = new HyphenatedRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan(),
            new LessOrEqualTo()
        );

        $tokenList = [
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 3),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 7),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::LABEL_STRING, 'alpha'),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 2),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 5)
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(
            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(1, 3, 7, new Label(Label::PRECEDENCE_ALPHA))
                ),
                new ComparatorVersion(
                    new LessOrEqualTo(),
                    new Version(2, 1, 5)
                )
            ),
            $parser->parse($tokenList)
        );
    }

    public function testValidHyphenatedRangeMajorMinorPatchLabelOnSecond()
    {
        $parser = new HyphenatedRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan(),
            new LessOrEqualTo()
        );

        $tokenList = [
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 3),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 7),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 2),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 5),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::LABEL_STRING, 'beta')
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(
            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(1, 3, 7)
                ),
                new ComparatorVersion(
                    new LessOrEqualTo(),
                    new Version(2, 1, 5, new Label(Label::PRECEDENCE_BETA))
                )
            ),
            $parser->parse($tokenList)
        );
    }

    public function testValidHyphenatedRangeMajorMinorPatchLabelOnBoth()
    {
        $parser = new HyphenatedRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan(),
            new LessOrEqualTo()
        );

        $tokenList = [
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 3),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 7),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::LABEL_STRING, 'alpha'),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 2),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 1),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 5),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::LABEL_STRING, 'beta')
        ];

        $this->assertTrue($parser->canParse($tokenList));
        $this->assertEquals(
            new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(1, 3, 7, new Label(Label::PRECEDENCE_ALPHA))
                ),
                new ComparatorVersion(
                    new LessOrEqualTo(),
                    new Version(2, 1, 5, new Label(Label::PRECEDENCE_BETA))
                )
            ),
            $parser->parse($tokenList)
        );
    }

    public function testNotHyphenatedRange()
    {
        $parser = new HyphenatedRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan(),
            new LessOrEqualTo()
        );

        $tokenList = [
            new Token(Token::DIGITS, 2)
        ];

        $this->assertFalse($parser->canParse($tokenList));
    }

    public function testNotHyphenatedRangeWithLabel()
    {
        $this->expectException('\RuntimeException');

        $parser = new HyphenatedRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan(),
            new LessOrEqualTo()
        );

        $tokenList = [
            new Token(Token::DIGITS, 2),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 0),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DIGITS, 0),
            new Token(Token::DOT_SEPARATOR, '.'),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::LABEL_STRING, 'alpha')
        ];

        $this->assertFalse($parser->canParse($tokenList));

        $parser->parse($tokenList);
    }

    public function testParseInvalidHyphenatedRangeThreeVersions()
    {
        $this->expectException('\RuntimeException');

        $parser = new HyphenatedRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan(),
            new LessOrEqualTo()
        );

        $tokenList = [
            new Token(Token::DIGITS, 2),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 3),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 5)
        ];

        $this->assertFalse($parser->canParse($tokenList));

        $parser->parse($tokenList);
    }

    public function testParseInvalidHyphenatedRangeFourVersions()
    {
        $this->expectException('\RuntimeException');

        $parser = new HyphenatedRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan(),
            new LessOrEqualTo()
        );

        $tokenList = [
            new Token(Token::DIGITS, 2),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 3),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 5),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 8)
        ];

        $this->assertFalse($parser->canParse($tokenList));

        $parser->parse($tokenList);
    }

    public function testParseInvalidHyphenatedRangeFiveVersions()
    {
        $this->expectException('\RuntimeException');

        $parser = new HyphenatedRangeParser(
            new VersionParser(new LabelBuilder()),
            new GreaterOrEqualTo(),
            new LessThan(),
            new LessOrEqualTo()
        );

        $tokenList = [
            new Token(Token::DIGITS, 2),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 3),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 5),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 8),
            new Token(Token::DASH_SEPARATOR, '-'),
            new Token(Token::DIGITS, 11)
        ];

        $this->assertFalse($parser->canParse($tokenList));

        $parser->parse($tokenList);
    }
}
