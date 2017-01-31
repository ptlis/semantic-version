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

use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Parse\VersionTokenizer;

final class TokenizeTest extends TestDataProvider
{
    /**
     * @dataProvider tokenProvider
     * @covers \ptlis\SemanticVersion\Parse\VersionTokenizer
     *
     * @param string $version
     * @param Token[] $tokenList
     */
    public function testTokenize($version, $tokenList)
    {
        $tokenizer = new VersionTokenizer();

        $this->assertEquals(
            $tokenList,
            $tokenizer->tokenize($version)
        );
    }
}
