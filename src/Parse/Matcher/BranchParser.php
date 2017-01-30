<?php

/**
 * PHP Version 5.3
 *
 * @copyright   (c) 2014-2017 brian ridley
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
 * Parser for composer branch ranges.
 *
 * Behaviour of branch ranges is described @ https://getcomposer.org/doc/02-libraries.md#branches
 */
class BranchParser implements RangeParserInterface
{
    /**
     * @var WildcardRangeParser
     */
    private $wildcardParser;


    /**
     * Constructor.
     *
     * @param WildcardRangeParser $wildcardParser
     */
    public function __construct(WildcardRangeParser $wildcardParser)
    {
        $this->wildcardParser = $wildcardParser;
    }

    /**
     * Returns true if the tokens can be parsed as a Packagist-style branch
     *
     * @param Token[] $tokenList
     *
     * @return boolean
     */
    public function canParse(array $tokenList)
    {
        $tokenListCount = count($tokenList);

        return $tokenListCount > 2
            && Token::LABEL_STRING === $tokenList[$tokenListCount - 1]->getType()
            && Token::DASH_SEPARATOR === $tokenList[$tokenListCount - 2]->getType()
            && Token::WILDCARD_DIGITS === $tokenList[$tokenListCount - 3]->getType();
    }

    /**
     * Build a ComparatorVersion representing the branch.
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function parse(array $tokenList)
    {
        return $this->wildcardParser->parse($tokenList);
    }
}
