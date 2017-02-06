<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Parse\RangeMatcher;

use ptlis\SemanticVersion\Parse\Token;

/**
 * Trait implementing method to chunk tokens by dash seperator.
 */
trait ChunkByDash
{
    /**
     * Chuck the tokens, splitting on hyphen.
     *
     * @param Token[] $tokenList
     * @param string $separator One of Token class constants
     *
     * @return Token[][]
     */
    private function chunk(array $tokenList, $separator = Token::DASH_SEPARATOR)
    {
        $tokenListCount = count($tokenList);
        $chunkedList = [];
        $accumulator = [];

        for ($i = 0; $i < $tokenListCount; $i++) {
            $token = $tokenList[$i];

            // Accumulate until we hit a dash
            if ($separator !== $token->getType()) {
                $accumulator[] = $token;

            } else {
                $chunkedList[] = $accumulator;
                $accumulator = [];
            }
        }

        if (count($accumulator)) {
            $chunkedList[] = $accumulator;
        }

        return $chunkedList;
    }
}