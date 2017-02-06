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

use ptlis\SemanticVersion\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Version\VersionBuilder;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Comparator versions store a comparator & version specifying part of a version range.
 */
final class ComparatorVersionParser implements RangeParserInterface
{
    use ChunkByDash;

    /** @var ComparatorFactory */
    private $comparatorFactory;

    /** @var VersionBuilder */
    private $versionBuilder;


    /**
     * Constructor.
     *
     * @param ComparatorFactory $comparatorFactory
     * @param VersionBuilder $versionBuilder
     */
    public function __construct(
        ComparatorFactory $comparatorFactory,
        VersionBuilder $versionBuilder
    ) {
        $this->comparatorFactory = $comparatorFactory;
        $this->versionBuilder = $versionBuilder;
    }

    /**
     * Returns true if the tokens can be parsed as a ComparatorVersion.
     *
     * @param Token[] $tokenList
     *
     * @return boolean
     */
    public function canParse(array $tokenList)
    {
        $canParse = false;

        // No illegal tokens present
        if (!$this->hasIllegalTokens($tokenList)) {
            $chunkedList = $this->chunk($tokenList);

            if (
                (1 === count($chunkedList) && Token::LABEL_STRING !== $chunkedList[0][0]->getType())
                || (2 === count($chunkedList) && Token::LABEL_STRING === $chunkedList[1][0]->getType())
            ) {
                $canParse = true;
            }
        }

        return $canParse;
    }

    /**
     * Build a ComparatorVersion representing the comparator & version.
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function parse(array $tokenList)
    {
        $comparatorList = [
            '<',
            '<=',
            '>',
            '>=',
            '='
        ];

        // Prefixed comparator, hydrate & remove
        if (count($tokenList) > 0 && in_array($tokenList[0]->getValue(), $comparatorList)) {
            $comparator = $this->comparatorFactory->get($tokenList[0]->getValue());
            $tokenList = array_slice($tokenList, 1);

        // Default to equality
        } else {
            $comparator = $this->comparatorFactory->get('=');
        }

        $chunkList = $this->chunk($tokenList);
        $versionTokenList = $chunkList[0];
        $labelTokenList = [];
        if (count($chunkList) > 1) {
            $labelTokenList = $chunkList[1];
        }

        return new ComparatorVersion(
            $comparator,
            $this->versionBuilder->buildFromTokens($versionTokenList, $labelTokenList)
        );
    }

    /**
     * Returns true if an illegal token is found.
     *
     * @param Token[] $tokenList
     *
     * @return boolean
     */
    public function hasIllegalTokens(array $tokenList)
    {
        $illegalTokenList = [
            Token::CARET_RANGE,
            Token::TILDE_RANGE,
            Token::WILDCARD_DIGITS,
            Token::LOGICAL_AND,
            Token::LOGICAL_OR
        ];

        $hasIllegalToken = false;
        foreach ($tokenList as $token) {
            if (in_array($token->getType(), $illegalTokenList)) {
                $hasIllegalToken = true;
            }
        }

        return $hasIllegalToken;
    }
}
