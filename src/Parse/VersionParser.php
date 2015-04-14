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

namespace ptlis\SemanticVersion\Parse;

use ptlis\SemanticVersion\Version\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Version\Comparator\ComparatorInterface;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\Label\LabelInterface;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\Version\VersionInterface;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;
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
     * @var ComparatorFactory
     */
    private $comparatorFactory;

    /**
     * @var VersionRangeParser
     */
    private $versionRangeParser;


    /**
     * Constructor.
     *
     * @param LabelBuilder $labelBuilder
     */
    public function __construct(LabelBuilder $labelBuilder)
    {
        $this->labelBuilder = $labelBuilder;
        $this->comparatorFactory = new ComparatorFactory(); // TODO: Inject
        $this->versionRangeParser = new VersionRangeParser(new ComparatorFactory(), $labelBuilder); // TODO: Inject
    }

    /**
     *
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function parse(array $tokenList)
    {
        $resultList = $this->clusterTokens($tokenList);

        // Parse out ComparatorVersions vs Logical operators
        $realResultList = array();
        for ($i = 0; $i < count($resultList); $i++) {

            // Version number only - effectively equals
            if ($resultList[$i] instanceof VersionInterface) {
                $realResultList[] = new ComparatorVersion(
                    $this->comparatorFactory->get('='),
                    $resultList[$i]
                );

            } elseif ($resultList[$i] instanceof ComparatorInterface) {
                $realResultList[] = new ComparatorVersion($resultList[$i], $resultList[$i + 1]);
                $i++;

            } else {
                $realResultList[] = $resultList[$i];
            }
        }
        $buildRange = new LogicalOperatorProcessor();

        return $buildRange->run($realResultList);
    }

    /**
     * Splits the array of tokens into smaller arrays, each one containing the tokens for a single version constraint.
     *
     * @param Token[] $tokenList
     *
     * @return Token[][] $tokenList
     */
    private function clusterTokens(array $tokenList)
    {
        $tokenClusterList = array();

        // Stores tokens not yet parcelled out
        $tokenAccumulator = array();

        for ($i = 0; $i < count($tokenList); $i++) {
            $currentToken = $tokenList[$i];

            switch (true) {

                // Terminating digit wildcard
                case Token::WILDCARD_DIGITS === $currentToken->getType():
                    $tokenAccumulator[] = $currentToken;
                    $tokenClusterList = array_merge(
                        $tokenClusterList,
                        $this->processClusteredTokenList($tokenAccumulator)
                    );
                    $tokenAccumulator = array();
                    break;

                // Beginning caret or tilde range
                case in_array($currentToken->getType(), array(Token::TILDE_RANGE, Token::CARET_RANGE)):
                    $tokenClusterList = array_merge(
                        $tokenClusterList,
                        $this->processClusteredTokenList($tokenAccumulator)
                    );
                    $tokenAccumulator = array();
                    $tokenAccumulator[] = $currentToken;
                    break;

                // Comparator or logical operator
                case !is_null($this->comparatorFactory->getFromToken($currentToken)):
                case in_array($currentToken->getType(), array(Token::LOGICAL_AND, Token::LOGICAL_OR)):
                    $tokenClusterList = array_merge(
                        $tokenClusterList,
                        $this->processClusteredTokenList($tokenAccumulator)
                    );
                    $tokenClusterList = array_merge(
                        $tokenClusterList,
                        $this->processClusteredTokenList(array($currentToken))
                    );
                    $tokenAccumulator = array();
                    break;

                // Any other case simply accumulate the token
                default:
                    $tokenAccumulator[] = $currentToken;
                    break;
            }
        }

        // Add any remaining tokens
        $tokenClusterList = array_merge(
            $tokenClusterList,
            $this->processClusteredTokenList($tokenAccumulator)
        );

        return $tokenClusterList;
    }

    /**
     * Process a cluster of tokens building version ranges.
     *
     * @param Token[] $tokenList
     *
     * @return mixed
     */
    private function processClusteredTokenList(array $tokenList)
    {
        $accumulatedTokenCount = count($tokenList);

        $tokenClusterList = array();

        if ($accumulatedTokenCount) {

            switch (true) {
                case !is_null($comparator = $this->comparatorFactory->getFromToken($tokenList[0])):
                    $tokenClusterList[] = $comparator;
                    break;

                case Token::DIGITS === $tokenList[0]->getType():

                    $hyphenated = false;

                    for ($i = 0; $i < count($tokenList); $i++) {
                        if (Token::DASH_SEPARATOR === $tokenList[$i]->getType()) {
                            $hyphenated = true;
                            break;
                        }
                    }

                    if ($hyphenated) {
                        $tokenClusterList[] = $this->versionRangeParser->getFromHyphenatedTokens($tokenList);

                    } elseif (Token::WILDCARD_DIGITS === $tokenList[$accumulatedTokenCount - 1]->getType()) {
                        $tokenClusterList[] = $this->versionRangeParser->getFromWildcardTokens(
                            $tokenList
                        );

                    } else {
                        $tokenClusterList[] = $this->versionRangeParser->getVersionFromTokens($tokenList);
                    }

                    break;

                case Token::TILDE_RANGE === $tokenList[0]->getType():
                    $tokenClusterList[] = $this->versionRangeParser->getFromTildeTokens(array_slice($tokenList, 1));
                    break;

                case Token::CARET_RANGE === $tokenList[0]->getType():
                    $tokenClusterList[] = $this->versionRangeParser->getFromCaretTokens(array_slice($tokenList, 1));
                    break;

                default:
                    $tokenClusterList[] = $tokenList[0];
                    break;
            }
        }

        return $tokenClusterList;
    }
}
