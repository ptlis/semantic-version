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
use ptlis\SemanticVersion\Comparator\EqualTo;
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\GreaterThan;
use ptlis\SemanticVersion\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;
use ptlis\SemanticVersion\VersionRange\LogicalOr;

class TestDataProvider extends TestCase
{
    /**
     * Test data, built to follow the specification used by composer:
     * https://getcomposer.org/doc/01-basic-usage.md#package-versions
     *
     * @return array
     */
    public function tokenProvider()
    {
        return [
            [
                'version_string' => '1',
                'tokens' => [
                    new Token(Token::DIGITS, '1')
                ],
                'parsed_range' => new ComparatorVersion(
                    new EqualTo(),
                    new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                ),
                'serialized' => '=1.0.0',
                'satisfies' => [
                    new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => 'v1',
                'tokens' => [
                    new Token(Token::DIGITS, '1')
                ],
                'parsed_range' => new ComparatorVersion(
                    new EqualTo(),
                    new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                ),
                'serialized' => '=1.0.0',
                'satisfies' => [
                    new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '1.2',
                'tokens' => [
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '2')
                ],
                'parsed_range' => new ComparatorVersion(
                    new EqualTo(),
                    new Version(1, 2, 0, new Label(Label::PRECEDENCE_ABSENT))
                ),
                'serialized' => '=1.2.0',
                'satisfies' => [
                    new Version(1, 2, 0, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '1.15.1',
                'tokens' => [
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '15'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '1')
                ],
                'parsed_range' => new ComparatorVersion(
                    new EqualTo(),
                    new Version(1, 15, 1, new Label(Label::PRECEDENCE_ABSENT))
                ),
                'serialized' => '=1.15.1',
                'satisfies' => [
                    new Version(1, 15, 1, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '> 5.4',
                'tokens' => [
                    new Token(Token::GREATER_THAN, '>'),
                    new Token(Token::DIGITS, '5'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '4')
                ],
                'parsed_range' => new ComparatorVersion(
                    new GreaterThan(),
                    new Version(5, 4, 0, new Label(Label::PRECEDENCE_ABSENT))
                ),
                'serialized' => '>5.4.0',
                'satisfies' => [
                    new Version(5, 5, 0, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '1.*',
                'tokens' => [
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::WILDCARD_DIGITS, '*')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(2, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.0.0,<2.0.0',
                'satisfies' => [
                    new Version(1, 5, 0, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '4.x',
                'tokens' => [
                    new Token(Token::DIGITS, '4'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::WILDCARD_DIGITS, 'x')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(4, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(5, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=4.0.0,<5.0.0',
                'satisfies' => [
                    new Version(4, 0, 0, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(4, 1, 0, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '1.5.*',
                'tokens' => [
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '5'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::WILDCARD_DIGITS, '*')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 5, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(1, 6, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.5.0,<1.6.0',
                'satisfies' => [
                    new Version(1, 5, 0, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(1, 5, 9, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '1.3.x',
                'tokens' => [
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '3'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::WILDCARD_DIGITS, 'x')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 3, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(1, 4, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.3.0,<1.4.0',
                'satisfies' => [
                    new Version(1, 3, 0, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(1, 3, 5, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '>2.0',
                'tokens' => [
                    new Token(Token::GREATER_THAN, '>'),

                    new Token(Token::DIGITS, '2'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0')
                ],
                'parsed_range' => new ComparatorVersion(
                    new GreaterThan(),
                    new Version(2, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                ),
                'serialized' => '>2.0.0',
                'satisfies' => [
                    new Version(2, 3, 0, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '>=1.2.1',
                'tokens' => [
                    new Token(Token::GREATER_THAN_EQUAL, '>='),

                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '2'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '1')
                ],
                'parsed_range' => new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version(1, 2, 1, new Label(Label::PRECEDENCE_ABSENT))
                ),
                'serialized' => '>=1.2.1',
                'satisfies' => [
                    new Version(1, 2, 1, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(7, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '<4.0.5',
                'tokens' => [
                    new Token(Token::LESS_THAN, '<'),

                    new Token(Token::DIGITS, '4'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '5')
                ],
                'parsed_range' => new ComparatorVersion(
                    new LessThan(),
                    new Version(4, 0, 5, new Label(Label::PRECEDENCE_ABSENT))
                ),
                'serialized' => '<4.0.5',
                'satisfies' => [
                    new Version(4, 0, 4, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '=1.0.1',
                'tokens' => [
                    new Token(Token::EQUAL_TO, '='),

                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '1')
                ],
                'parsed_range' => new ComparatorVersion(
                    new EqualTo(),
                    new Version(1, 0, 1, new Label(Label::PRECEDENCE_ABSENT))
                ),
                'serialized' => '=1.0.1',
                'satisfies' => [
                    new Version(1, 0, 1, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '>=1.0.1,<2.0.0',
                'tokens' => [
                    new Token(Token::GREATER_THAN_EQUAL, '>='),

                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '1'),

                    new Token(Token::LOGICAL_AND, ','),

                    new Token(Token::LESS_THAN, '<'),

                    new Token(Token::DIGITS, '2'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 1, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(2, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.0.1,<2.0.0',
                'satisfies' => [
                    new Version(1, 0, 1, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(1, 9999, 9999, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],

            [
                'version_string' => '>=1.0.1<2.0.0',
                'tokens' => [
                    new Token(Token::GREATER_THAN_EQUAL, '>='),

                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '1'),

                    new Token(Token::LOGICAL_AND, ''),

                    new Token(Token::LESS_THAN, '<'),

                    new Token(Token::DIGITS, '2'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 1, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(2, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.0.1,<2.0.0',
                'satisfies' => [
                    new Version(1, 0, 1, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(1, 9999, 9999, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '~1.7',
                'tokens' => [
                    new Token(Token::TILDE_RANGE, '~'),
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '7')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 7, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(2, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.7.0,<2.0.0',
                'satisfies' => [
                    new Version(1, 7, 0, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(1, 87, 1, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '~1.7.4',
                'tokens' => [
                    new Token(Token::TILDE_RANGE, '~'),
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '7'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '4'),
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 7, 4, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(1, 8, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.7.4,<1.8.0',
                'satisfies' => [
                    new Version(1, 7, 4, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(1, 7, 99, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '^3.1.0',
                'tokens' => [
                    new Token(Token::CARET_RANGE, '^'),
                    new Token(Token::DIGITS, '3'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(3, 1, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(4, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=3.1.0,<4.0.0',
                'satisfies' => [
                    new Version(3, 1, 0, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(3, 99, 1, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '1.0.7-3.1.18',
                'tokens' => [
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
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 7, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessOrEqualTo(),
                        new Version(3, 1, 18, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.0.7,<=3.1.18',
                'satisfies' => [
                    new Version(1, 0, 7, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(3, 1, 18, new Label(Label::PRECEDENCE_ABSENT))
                ],
            ],
            [
                'version_string' => '1.0-2.0',
                'tokens' => [
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::DIGITS, '2'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(2, 1, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.0.0,<2.1.0',
                'satisfies' => [
                    new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(2, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '1-2',
                'tokens' => [
                    new Token(Token::DIGITS, '1'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::DIGITS, '2'),
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(3, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.0.0,<3.0.0',
                'satisfies' => [
                    new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(2, 9999, 9999, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '1.0-2.0',
                'tokens' => [
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::DIGITS, '2'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(2, 1, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.0.0,<2.1.0',
                'satisfies' => [
                    new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(2, 0, 847, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],

            // Test compatibility with packigist-style -dev version numbers
            // See https://getcomposer.org/doc/02-libraries.md#branches for meaning
            [
                'version_string' => '3.7.x-dev',
                'tokens' => [
                    new Token(Token::DIGITS, '3'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '7'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::WILDCARD_DIGITS, 'x'),

                    new Token(Token::DASH_SEPARATOR, '-'),
                    new Token(Token::LABEL_STRING, 'dev')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(3, 7, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(3, 8, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=3.7.0,<3.8.0',
                'satisfies' => [

                ]
            ],
            [
                'version_string' => '1.8.3-alpha.7',
                'tokens' => [
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '8'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '3'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::LABEL_STRING, 'alpha'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '7')
                ],
                'parsed_range' => new ComparatorVersion(
                    new EqualTo(),
                    new Version(1, 8, 3, new Label(Label::PRECEDENCE_ALPHA, 7))
                ),
                'serialized' => '=1.8.3-alpha.7',
                'satisfies' => [
                    new Version(1, 8, 3, new Label(Label::PRECEDENCE_ALPHA, 7))
                ]
            ],
            [
                'version_string' => '1.8.0-alpha.7-1.8.0-beta.2',
                'tokens' => [
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '8'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::LABEL_STRING, 'alpha'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '7'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '8'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::LABEL_STRING, 'beta'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '2')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 8, 0, new Label(Label::PRECEDENCE_ALPHA, 7))
                    ),
                    new ComparatorVersion(
                        new LessOrEqualTo(),
                        new Version(1, 8, 0, new Label(Label::PRECEDENCE_BETA, 2))
                    )
                ),
                'serialized' => '>=1.8.0-alpha.7,<=1.8.0-beta.2',
                'satisfies' => [
                    new Version(1, 8, 0, new Label(Label::PRECEDENCE_ALPHA, 8)),
                    new Version(1, 8, 0, new Label(Label::PRECEDENCE_BETA, 1))
                ]
            ],
            [
                'version_string' => '>=1.7.0 <1.9',
                'tokens' => [
                    new Token(Token::GREATER_THAN_EQUAL, '>='),
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '7'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),

                    new Token(Token::LOGICAL_AND, ''),

                    new Token(Token::LESS_THAN, '<'),
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '9')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 7, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(1, 9, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.7.0,<1.9.0',
                'satisfies' => [
                    new Version(1, 7, 0, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(1, 8, 9, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '>=1.7.0 , <1.9',
                'tokens' => [
                    new Token(Token::GREATER_THAN_EQUAL, '>='),
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '7'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),

                    new Token(Token::LOGICAL_AND, ','),

                    new Token(Token::LESS_THAN, '<'),
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '9')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 7, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessThan(),
                        new Version(1, 9, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.7.0,<1.9.0',
                'satisfies' => [
                    new Version(1, 7, 0, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(1, 8, 9, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '1.7.6 | >1.9',
                'tokens' => [
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '7'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '6'),

                    new Token(Token::LOGICAL_OR, '|'),

                    new Token(Token::GREATER_THAN, '>'),
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '9')
                ],
                'parsed_range' => new LogicalOr(
                    new ComparatorVersion(
                        new EqualTo(),
                        new Version(1, 7, 6, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new GreaterThan(),
                        new Version(1, 9, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '=1.7.6|>1.9.0',
                'satisfies' => [
                    new Version(1, 7, 6, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(1, 9, 1, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '>1.5 < 4 | >=5 <6',
                'tokens' => [
                    new Token(Token::GREATER_THAN, '>'),
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '5'),

                    new Token(Token::LOGICAL_AND, ''),

                    new Token(Token::LESS_THAN, '<'),
                    new Token(Token::DIGITS, '4'),

                    new Token(Token::LOGICAL_OR, '|'),

                    new Token(Token::GREATER_THAN_EQUAL, '>='),
                    new Token(Token::DIGITS, '5'),

                    new Token(Token::LOGICAL_AND, ''),

                    new Token(Token::LESS_THAN, '<'),
                    new Token(Token::DIGITS, '6')
                ],
                'parsed_range' => new LogicalOr(
                    new LogicalAnd(
                        new ComparatorVersion(
                            new GreaterThan(),
                            new Version(1, 5, 0, new Label(Label::PRECEDENCE_ABSENT))
                        ),
                        new ComparatorVersion(
                            new LessThan(),
                            new Version(4, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                        )
                    ),
                    new LogicalAnd(
                        new ComparatorVersion(
                            new GreaterOrEqualTo(),
                            new Version(5, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                        ),
                        new ComparatorVersion(
                            new LessThan(),
                            new Version(6, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                        )
                    )
                ),
                'serialized' => '>1.5.0,<4.0.0|>=5.0.0,<6.0.0',
                'satisfies' => [
                    new Version(1, 5, 1, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(3, 9, 0, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(5, 0, 0, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(5, 9, 0, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '1.0.0-rc.2-2.0.0',
                'tokens' => [
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DASH_SEPARATOR, '-'),
                    new Token(Token::LABEL_STRING, 'rc'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '2'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::DIGITS, '2'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2))
                    ),
                    new ComparatorVersion(
                        new LessOrEqualTo(),
                        new Version(2, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    )
                ),
                'serialized' => '>=1.0.0-rc.2,<=2.0.0',
                'satisfies' => [
                    new Version(1, 0, 0, new Label(Label::PRECEDENCE_RC, 2)),
                    new Version(1, 9, 1, new Label(Label::PRECEDENCE_ABSENT))
                ]
            ],
            [
                'version_string' => '1.0.0-2.0.0-alpha.3',
                'tokens' => [
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::DIGITS, '2'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '0'),
                    new Token(Token::DASH_SEPARATOR, '-'),
                    new Token(Token::LABEL_STRING, 'alpha'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '3')
                ],
                'parsed_range' => new LogicalAnd(
                    new ComparatorVersion(
                        new GreaterOrEqualTo(),
                        new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT))
                    ),
                    new ComparatorVersion(
                        new LessOrEqualTo(),
                        new Version(2, 0, 0, new Label(Label::PRECEDENCE_ALPHA, 3))
                    )
                ),
                'serialized' => '>=1.0.0,<=2.0.0-alpha.3',
                'satisfies' => [
                    new Version(1, 0, 0, new Label(Label::PRECEDENCE_ABSENT)),
                    new Version(2, 0, 0, new Label(Label::PRECEDENCE_ALPHA, 3))
                ]
            ]
        ];
    }
}
