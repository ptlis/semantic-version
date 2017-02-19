<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Parse;

/**
 * Trait implementing method to chunk token lists into multiple smaller lists by a specified token.
 */
trait ChunkBySeparator
{
    /**
     * Chuck the tokens, splitting on the specified token types.
     *
     * @param Token[] $tokenList
     * @param string[] $separatorList Array of Token class constants
     *
     * @return Token[][]
     */
    private function chunk(
        array $tokenList,
        array $separatorList
    ) {
        $chunkList = [];
        $accumulator = [];

        // Split token stream by dash separators
        for ($i = 0; $i < count($tokenList); $i++) {
            if (in_array($tokenList[$i]->getType(), $separatorList)) {
                $chunkList[] = $accumulator;
                $chunkList[] = [$tokenList[$i]];
                $accumulator = [];
            } else {
                $accumulator[] = $tokenList[$i];
            }
        }

        if (count($accumulator)) {
            $chunkList[] = $accumulator;
        }

        return $chunkList;
    }
}
