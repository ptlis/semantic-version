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
use ptlis\SemanticVersion\Version\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Version\Comparator\LessThan;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Label\LabelAbsent;
use ptlis\SemanticVersion\Version\Label\LabelAlpha;
use ptlis\SemanticVersion\Version\Version;

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
                array(
                    new Version(1, 0, 0, new LabelAbsent())
                )
            ),
            array(
                'v1',
                array(
                    new Token(Token::DIGITS, '1')
                ),
                array(
                    new Version(1, 0, 0, new LabelAbsent())
                )
            ),
            array(
                '1.2',
                array(
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::DIGITS, '2')
                ),
                array(
                    new Version(1, 2, 0, new LabelAbsent())
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
                array(
                    new Version(1, 15, 1, new LabelAbsent())
                )
            ),
            array(
                '1.*',
                array(
                    new Token(Token::DIGITS, '1'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::WILDCARD_DIGITS, '*')
                ),
                array(
                    new GreaterOrEqualTo(),
                    new Version(1, 0, 0),
                    new LessThan(),
                    new Version(2, 0, 0)
                )
            ),
            array(
                '4.x',
                array(
                    new Token(Token::DIGITS, '4'),
                    new Token(Token::DOT_SEPARATOR, '.'),
                    new Token(Token::WILDCARD_DIGITS, 'x')
                ),
                array(
                    new GreaterOrEqualTo(),
                    new Version(4, 0, 0),
                    new LessThan(),
                    new Version(5, 0, 0)
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
                array(
                    new GreaterOrEqualTo(),
                    new Version(1, 5, 0),
                    new LessThan(),
                    new Version(1, 6, 0)
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
                array(
                    new GreaterOrEqualTo(),
                    new Version(1, 3, 0),
                    new LessThan(),
                    new Version(1, 4, 0)
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
                array(
                    new GreaterThan(),
                    new Version(2, 0)
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
                array(
                    new GreaterOrEqualTo(),
                    new Version(1, 2, 1)
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
                array(
                    new LessThan(),
                    new Version(4, 0, 5)
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
                array(
                    new EqualTo(),
                    new Version(1, 0, 1)
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
                array(
                    new GreaterOrEqualTo(),
                    new Version(1, 0, 1),
                    new LessThan(),
                    new Version(2, 0, 0),
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
                array(
                    new GreaterOrEqualTo(),
                    new Version(1, 7, 0),
                    new LessThan(),
                    new Version(2, 0, 0),
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
                array(
                    new GreaterOrEqualTo(),
                    new Version(1, 7, 4),
                    new LessThan(),
                    new Version(1, 8, 0),
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
                array(
                    new GreaterOrEqualTo(),
                    new Version(3, 1, 0),
                    new LessThan(),
                    new Version(4, 0, 0),
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
                array(
                    new GreaterOrEqualTo(),
                    new Version(1, 0, 7),
                    new LessThan(),
                    new Version(3, 1, 18)
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
                array(
                    new GreaterOrEqualTo(),
                    new Version(1, 0, 0),
                    new LessThan(),
                    new Version(2, 1, 0)
                )
            ),
            array(
                '1-2',
                array(
                    new Token(Token::DIGITS, '1'),

                    new Token(Token::DASH_SEPARATOR, '-'),

                    new Token(Token::DIGITS, '2'),
                ),
                array(
                    new GreaterOrEqualTo(),
                    new Version(1, 0, 0),
                    new LessThan(),
                    new Version(3, 0, 0)
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
                array(
                    new Version(1, 8, 3, new LabelAlpha(7)),
                )
            )
        );
    }
}
