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

/**
 * @covers \ptlis\SemanticVersion\Parse\Token
 */
final class TokenTest extends TestCase
{
    public function testCreate()
    {
        $token = new Token(Token::DIGITS, 1);

        $this->assertSame(Token::DIGITS, $token->getType());
        $this->assertSame(1, $token->getValue());
    }
}