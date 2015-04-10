<?php

/**
 * PHP Version 5.3
 *
 * @copyright   (c) 2015 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test\Parse;

use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Version\Comparator\EqualTo;
use ptlis\SemanticVersion\Version\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Version\Comparator\GreaterThan;
use ptlis\SemanticVersion\Version\Comparator\LessThan;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;

class TestDataProvider extends \PHPUnit_Framework_TestCase
{
    /**
     * Test data, built to follow the specification used by composer:
     * https://getcomposer.org/doc/01-basic-usage.md#package-versions
     *
     * @return array
     */
    public function tokenProvider()
    {
        return array(
            array(
                '1',
                array(
                    new Token(Token::DIGITS, '1')
                ),
                new ComparatorVersion(
                    new EqualTo(),
                    new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                )
            ),
            array(
                'v1',
                array(
                    new Token(Token::DIGITS, '1')
                ),
                new ComparatorVersion(
                    new EqualTo(),
                    new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                )
            ),
            array(
                '1.2',
                array(
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '2')
                ),
                new ComparatorVersion(
                    new EqualTo(),
                    new Version(1, 2, 0, new Label(Label::PRECEDENCE_ABSENT))
                )
            ),
            array(
                '1.15.1',
                array(
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '15'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '1')
                ),
                new ComparatorVersion(
                    new EqualTo(),
                    new Version(1, 15, 1, new Label(Label::PRECEDENCE_ABSENT))
                )
            ),
            array(
                '1.*',
                array(
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::WILDCARD_DIGITS, '*')
                ),
                new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(2, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                )
            ),
            array(
                '4.x',
                array(
                    new Token(Token::DIGITS, '4'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::WILDCARD_DIGITS, 'x')
                ),
                new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(4, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(5, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                )
            ),
            array(
                '1.5.*',
                array(
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '5'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::WILDCARD_DIGITS, '*')
                ),
                new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 5, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(1, 6, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                )
            ),
            array(
                '1.3.x',
                array(
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '3'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::WILDCARD_DIGITS, 'x')
                ),
                new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 3, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(1, 4, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                )
            ),
            array(
                '>2.0',
                array(
                    new Token(Token::GREATER_THAN, '>'),

                    new Token(Token::DIGITS, '2'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0')
                ),
                new ComparatorVersion(
                    new GreaterThan(),
                    new Version(2, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                )
            ),
            array(
                '>=1.2.1',
                array(
                    new Token(Token::GREATER_THAN_EQUAL, '>='),

                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '2'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '1')
                ),
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(1, 2, 1, new Label(Label::PRECEDENCE_ABSENT))
                )
            ),
            array(
                '<4.0.5',
                array(
                    new Token(Token::LESS_THAN, '<'),

                    new Token(Token::DIGITS, '4'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '5')
                ),
                new ComparatorVersion(
                    new LessThan(),
                    new Version(4, 0, 5, new Label(Label::PRECEDENCE_ABSENT))
                )
            ),
            array(
                '=1.0.1',
                array(
                    new Token(Token::EQUAL_TO, '='),

                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '1')
                ),
                new ComparatorVersion(
                    new EqualTo(),
                    new Version(1, 0, 1, new Label(Label::PRECEDENCE_ABSENT))
                )
            ),
            array(
                '>=1.0.1<2.0.0',
                array(
                    new Token(Token::GREATER_THAN_EQUAL, '>='),

                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '1'),

                    new Token(Token::LESS_THAN, '<'),

                    new Token(Token::DIGITS, '2'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0')
                ),
                new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 1, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(2, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                )
            ),
            array(
                '~1.7',
                array(
                    new Token(Token::TILDE_RANGE, '~'),
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '7')
                ),
                new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 7, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(2, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                )
            ),
            array(
                '~1.7.4',
                array(
                    new Token(Token::TILDE_RANGE, '~'),
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '7'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '4'),
                ),
                new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 7, 4, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(1, 8, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                )
            ),
            array(
                '^3.1.0',
                array(
                    new Token(Token::CARET_RANGE, '^'),
                    new Token(Token::DIGITS, '3'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                ),
                new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(3, 1, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(4, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                )
            ),
            array(
                '1.0.7-3.1.18',
                array(
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '7'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::DIGITS, '3'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '18')
                ),
                new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 7, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(3, 1, 18, new Label(Label::PRECEDENCE_ABSENT))
                    )
                )
            ),
            array(
                '1.0-2.0',
                array(
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::DIGITS, '2'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0')
                ),
                new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(2, 1, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                )
            ),
            array(
                '1-2',
                array(
                    new Token(Token::DIGITS, '1'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::DIGITS, '2'),
                ),
                new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(3, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                )
            ),
            array(
                '1.8.3-alpha.7',
                array(
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '8'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '3'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::LABEL_STRING, 'alpha'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '7')
                ),
                new ComparatorVersion(
                    new EqualTo(),
                    new Version(1, 8, 3, new Label(Label::PRECEDENCE_ALPHA, 7))
                )
            )
        );
    }
}
