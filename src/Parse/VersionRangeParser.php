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

use ptlis\SemanticVersion\Parse\RangeParser\RangeParserInterface;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Parser accepting array of tokens and returning an array of comparators & versions.
 */
final class VersionRangeParser
{
    use ChunkBySeparator;

    /** @var LogicalOperatorProcessor */
    private $logicalOperatorProcessor;

    /** @var RangeParserInterface[] */
    private $rangeParserList;

    /** @var string[] Array of tokens representing logical operators */
    private $operatorTokenList = [
        Token::LOGICAL_AND,
        Token::LOGICAL_OR
    ];


    /**
     * Constructor
     *
     * @param LogicalOperatorProcessor $logicalOperatorProcessor
     * @param RangeParserInterface[] $rangeParserList
     */
    public function __construct(
        LogicalOperatorProcessor $logicalOperatorProcessor,
        array $rangeParserList
    ) {
        $this->logicalOperatorProcessor = $logicalOperatorProcessor;
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
        $realResultList = [];
        $tokenClusterList = $this->chunk($tokenList, $this->operatorTokenList);
        foreach ($tokenClusterList as $tokenCluster) {
            $parsed = $this->attemptParse($tokenCluster);

            if (is_null($parsed)) {
                if (in_array($tokenCluster[0]->getType(), $this->operatorTokenList)) {
                    $realResultList[] = $tokenCluster[0];
                } else {
                    throw new \RuntimeException('Unable to parse version string');
                }
            } else {
                $realResultList[] = $parsed;
            }
        }

        return $this->logicalOperatorProcessor->run($realResultList);
    }

    /**
     * Attempt to parse the token list as a version range into an object implementing VersionRangeInterface
     *
     * Iterates through the provided range parsers checking to see if they can parse the token list. If they can then we
     * call the parse method and return a version range object, otherwise return null.
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface|null
     */
    private function attemptParse(array $tokenList)
    {
        $parsed = null;
        foreach ($this->rangeParserList as $rangeParser) {
            if ($rangeParser->canParse($tokenList)) {
                $parsed = $rangeParser->parse($tokenList);
                break;
            }
        }

        return $parsed;
    }
}
