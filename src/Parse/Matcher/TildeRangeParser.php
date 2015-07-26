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

namespace ptlis\SemanticVersion\Parse\Matcher;

use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Parser for tilde ranges.
 *
 * Behaviour of caret ranges is described @ https://getcomposer.org/doc/articles/versions.md#tilde
 */
class TildeRangeParser implements RangeParserInterface
{
    /**
     * Returns true if the provided tokens represent a tilde range.
     *
     * @param Token[] $tokenList
     *
     * @return boolean
     */
    public function canParse(array $tokenList)
    {
        return count($tokenList) > 0
            && Token::TILDE_RANGE === $tokenList[0]->getType();
    }

    /**
     * Build a comparator version representing the tilde range.
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function parse(array $tokenList)
    {
        $parser = new WildcardRangeParser();

        return $parser->parse(array_slice($tokenList, 1));
    }
}
