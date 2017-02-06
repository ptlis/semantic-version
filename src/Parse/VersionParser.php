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

use ptlis\SemanticVersion\Parse\RangeMatcher\RangeParserInterface;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Parser accepting array of tokens and returning an array of comparators & versions.
 *
 * @todo Correctly validate versions
 */
final class VersionParser
{
    /** @var RangeParserInterface[] */
    private $rangeParserList;


    /**
     * Constructor.
     *
     * @param RangeParserInterface[] $rangeParserList
     */
    public function __construct(array $rangeParserList)
    {
        $this->rangeParserList = $rangeParserList;
    }

    /**
     * Parse a version range.
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function parseRange(array $tokenList)
    {
        $clusteredTokenList = $this->clusterTokens($tokenList);

        $operatorList = [
            Token::LOGICAL_AND,
            Token::LOGICAL_OR
        ];

        $realResultList = [];
        foreach ($clusteredTokenList as $clusteredTokens) {

            $parsed = null;
            foreach ($this->rangeParserList as $rangeParser) {
                if ($rangeParser->canParse($clusteredTokens)) {
                    $parsed = $rangeParser->parse($clusteredTokens);
                    break;
                }
            }

            if (is_null($parsed)) {

                if (in_array($clusteredTokens[0]->getType(), $operatorList)) {
                    $realResultList[] = $clusteredTokens[0];


                } else {
                    throw new \RuntimeException('Unable to parse version string');
                }
            } else {
                $realResultList[] = $parsed;
            }

        }

        $buildRange = new LogicalOperatorProcessor();

        return $buildRange->run($realResultList);
    }

    /**
     * Clusters the tokens, breaking them up upon finding a logical AND / OR.
     *
     * @param Token[] $tokenList
     *
     * @return Token[][] $tokenList
     */
    private function clusterTokens(array $tokenList)
    {
        $comparatorTokenList = [
            Token::LOGICAL_AND,
            Token::LOGICAL_OR
        ];

        $tokenClusterList = [];

        // Stores tokens not yet parcelled out
        $tokenAccumulator = [];
        $tokenListCount = count($tokenList);
        for ($i = 0; $i < $tokenListCount; $i++) {
            $currentToken = $tokenList[$i];

            if (in_array($currentToken->getType(), $comparatorTokenList)) {
                $tokenClusterList[] = $tokenAccumulator;
                $tokenClusterList[] = [$currentToken];
                $tokenAccumulator = [];

            } else {
                $tokenAccumulator[] = $currentToken;
            }
        }

        // Add any remaining tokens
        if (count($tokenAccumulator)) {
            $tokenClusterList[] = $tokenAccumulator;
        }

        return $tokenClusterList;
    }
}
