<?php

/**
 * PHP Version 5.3
 *
 * @copyright   (c) 2014-2015 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Parse;

use ptlis\SemanticVersion\Parse\Matcher\BranchParser;
use ptlis\SemanticVersion\Parse\Matcher\CaretRangeParser;
use ptlis\SemanticVersion\Parse\Matcher\ComparatorVersionParser;
use ptlis\SemanticVersion\Parse\Matcher\HyphenatedRangeParser;
use ptlis\SemanticVersion\Parse\Matcher\RangeParserInterface;
use ptlis\SemanticVersion\Parse\Matcher\TildeRangeParser;
use ptlis\SemanticVersion\Parse\Matcher\WildcardRangeParser;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Parser accepting array of tokens and returning an array of comparators & versions.
 *
 * @todo Correctly validate versions
 */
class VersionParser
{
    /**
     * @var LabelBuilder
     */
    private $labelBuilder;


    /**
     * Constructor.
     *
     * @param LabelBuilder $labelBuilder
     */
    public function __construct(LabelBuilder $labelBuilder)
    {
        $this->labelBuilder = $labelBuilder;
    }

    /**
     *
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function parseRange(array $tokenList)
    {
        $clusteredTokenList = $this->clusterTokens($tokenList);

        $operatorList = array(
            Token::LOGICAL_AND,
            Token::LOGICAL_OR
        );

        /** @var RangeParserInterface[] */
        $matcherList = array(
            new CaretRangeParser(),
            new TildeRangeParser(),
            new WildcardRangeParser(),
            new BranchParser(),
            new ComparatorVersionParser(),
            new HyphenatedRangeParser()
        );

        $realResultList = array();
        foreach ($clusteredTokenList as $clusteredTokens) {

            $parsed = null;
            foreach ($matcherList as $matcher) {
                if ($matcher->canParse($clusteredTokens)) {
                    $parsed = $matcher->parse($clusteredTokens);
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
        $comparatorTokenList = array(
            Token::LOGICAL_AND,
            Token::LOGICAL_OR
        );

        $tokenClusterList = array();

        // Stores tokens not yet parcelled out
        $tokenAccumulator = array();
        $tokenListCount = count($tokenList);
        for ($i = 0; $i < $tokenListCount; $i++) {
            $currentToken = $tokenList[$i];

            if (in_array($currentToken->getType(), $comparatorTokenList)) {
                $tokenClusterList[] = $tokenAccumulator;
                $tokenClusterList[] = array($currentToken);
                $tokenAccumulator = array();

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
