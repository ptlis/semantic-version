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

use ptlis\SemanticVersion\Version\Comparator\ComparatorInterface;
use ptlis\SemanticVersion\Version\Comparator\EqualTo;
use ptlis\SemanticVersion\Version\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Version\Comparator\GreaterThan;
use ptlis\SemanticVersion\Version\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Version\Comparator\LessThan;
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
     * Constructor.
     *
     * @param LabelBuilder $labelBuilder
     */
    public function __construct(LabelBuilder $labelBuilder)
    {
        $this->labelBuilder = $labelBuilder;
    }

    /**
     * @todo Return VersionRange instances?
     *
     * @param Token[] $tokenList
     *
     * @return array of Versions and comparators
     */
    public function parse(array $tokenList)
    {
        $tokenClusterList = $this->clusterTokens($tokenList);

        $resultList = array();

        foreach ($tokenClusterList as $tokenCluster) {
            $resultList = array_merge(
                $resultList,
                $this->parseCluster($tokenCluster)
            );
        }

        return $this->hydrateRanges($resultList);
    }

    /**
     * Hydrate a version list from Version & Comparator instances.
     *
     * @param array $resultList
     *
     * @return VersionRangeInterface
     */
    private function hydrateRanges(array $resultList)
    {
        $range = null;

        if (count($resultList) == 1) {
            $range = new ComparatorVersion(new EqualTo(), $resultList[0]);

        } elseif (count($resultList) >= 2) {
            $comparator = $resultList[0];
            $version = $resultList[1];

            // Invalid version range
            if (!($comparator instanceof ComparatorInterface) || !($version instanceof VersionInterface)) {
                throw new \RuntimeException('Invalid version range');
            }

            $leftRange = new ComparatorVersion($comparator, $version);

            // TODO: handle and/or

            $continueList = array_slice($resultList, 2);
            if (count($continueList)) {
                $rightRange = $this->hydrateRanges($continueList);

                $range = new LogicalAnd($leftRange, $rightRange);

            } else {
                $range = $leftRange;
            }
        }

        return $range;
    }

    /**
     * @param Token[] $tokenList
     */
    private function parseCluster(array $tokenList)
    {
        $versionTokenList = array();
        $labelTokenList = array();

        $inLabel = false;
        $inRange = false;

        // Parse versions beginning with comparator, caret or tilde
        $resultList = $this->getRangedVersion($tokenList);

        // If no tokens were found then we have either a simple version or a hyphenated range
        if (!count($resultList)) {
            for ($i = 0; $i < count($tokenList); $i++) {
                $currentToken = $tokenList[$i];

                // Special handling for dash separator - may be label or version range
                if (Token::DASH_SEPARATOR === $currentToken->getType()) {

                    // Peek ahead - if not a label then we're dealing with the first part of a hyphenated range
                    if ($i + 1 < count($tokenList) && Token::LABEL_STRING !== $tokenList[$i + 1]->getType()) {

                        $resultList[] = new GreaterOrEqualTo();

                        // TODO: Check for final wildcard...
                        $resultList[] = $this->getVersionFromTokens($versionTokenList, $labelTokenList);
                        $inRange = true;
                        $versionTokenList = array();
                        $labelTokenList = array();

                    } else {
                        $inLabel = true;
                    }

                // Otherwise accumulate version tokens
                } else {
                    if (!$inLabel) {
                        $versionTokenList[] = $currentToken;
                    } else {
                        $labelTokenList[] = $currentToken;
                    }
                }
            }
        }

        // Handle upper bounds for hyphenated ranges
        if ($inRange) {
            $resultList[] = new LessThan();
            $resultList[] = $this->getUpperVersionForHyphenRange($versionTokenList, $labelTokenList);

        // Otherwise handle simple or wildcard version number
        } elseif ($versionTokenList) {

            if (Token::WILDCARD_DIGITS === $versionTokenList[count($versionTokenList) - 1]->getType()) {
                $resultList = array_merge(
                    $resultList,
                    $this->getWildcardVersionFromTokens($versionTokenList)
                );
            } else {
                $resultList[] = $this->getVersionFromTokens($versionTokenList, $labelTokenList);
            }
        }

        return $resultList;
    }

    /**
     * @todo Return VersionRange instances?
     *
     * Hyphenated ranges are implemented as described @ https://getcomposer.org/doc/01-basic-usage.md#package-versions
     *
     * @param Token[] $tokenList
     * @param Token[] $labelTokenList
     *
     * @return array of Versions and comparators
     */
    private function getUpperVersionForHyphenRange(array $tokenList, array $labelTokenList)
    {
        $major = 0;
        $minor = 0;
        $patch = 0;

        switch (count($tokenList)) {
            case 1:
                $major = $tokenList[0]->getValue() + 1;
                break;

            case 3:
                $major = $tokenList[0]->getValue();
                $minor = $tokenList[2]->getValue() + 1;
                break;

            case 5:
                $major = $tokenList[0]->getValue();
                $minor = $tokenList[2]->getValue();
                $patch = $tokenList[4]->getValue();
                break;

            default:
                throw new \RuntimeException('Invalid version'); // TODO: Handle earlier in validation step
                break;
        }

        return new Version(
            $major,
            $minor,
            $patch,
            $this->getLabelFromTokens($labelTokenList)
        );
    }

    /**
     * @todo Return VersionRange instances?
     *
     * @param Token[] $tokenList
     *
     * @return array of Versions and comparators
     */
    private function getRangedVersion(array $tokenList)
    {
        $resultList = array();

        // Tilde range
        if (Token::TILDE_RANGE === $tokenList[0]->getType()) {
            $resultList = $this->getTildeVersionFromTokens(
                array_slice($tokenList, 1)
            );

        // Caret range
        } elseif (Token::CARET_RANGE === $tokenList[0]->getType()) {
            $resultList = $this->getCaretVersionFromTokens(
                array_slice($tokenList, 1)
            );

        // Any comparator
        } elseif (!is_null($this->getComparatorByTokenType($tokenList[0]))) {
            $resultList[] = $this->getComparatorByTokenType($tokenList[0]);
            $resultList[] = $this->getVersionFromTokens(
                array_slice($tokenList, 1)
            );
        }

        return $resultList;
    }

    /**
     * Splits the array of tokens into smaller arrays, each one containing the tokens for a single version constraint.
     *
     * @param Token[] $tokenList
     *
     * @return Token[][] $tokenList
     */
    public function clusterTokens(array $tokenList)
    {
        $tokenClusterList = array();

        // Stores tokens not yet parcelled out
        $tokenAccumulator = array();

        $addClusteredTokens = function($accumulatedTokenList) use (&$tokenClusterList) {
            if (count($accumulatedTokenList)) {
                $tokenClusterList[] = $accumulatedTokenList;
            }
        };

        for ($i = 0; $i < count($tokenList); $i++) {
            $currentToken = $tokenList[$i];

            switch (true) {

                // Terminating digit wildcard
                case Token::WILDCARD_DIGITS === $currentToken->getType():
                    $tokenAccumulator[] = $currentToken;
                    $addClusteredTokens($tokenAccumulator);
                    $tokenAccumulator = array();
                    break;

                // Beginning caret, tilde or comparator
                case in_array($currentToken->getType(), array(Token::TILDE_RANGE, Token::CARET_RANGE)):
                case !is_null($this->getComparatorByTokenType($currentToken)):
                    $addClusteredTokens($tokenAccumulator);
                    $tokenAccumulator = array();
                    $tokenAccumulator[] = $currentToken;
                    break;

                // Any other case simply accumulate the token
                default:
                    $tokenAccumulator[] = $currentToken;
                    break;

                // TODO: OR & AND
            }
        }

        // Add any remaining tokens
        $addClusteredTokens($tokenAccumulator);

        return $tokenClusterList;
    }

    /**
     * Get a Version instance from version tokens.
     *
     * @param Token[] $versionTokenList
     * @param Token[] $labelTokenList
     *
     * @return Version
     */
    private function getVersionFromTokens(array $versionTokenList, array $labelTokenList = array())
    {
        // TODO: Builder?

        $major = $versionTokenList[0]->getValue();
        $minor = 0;
        $patch = 0;
        $label = null;

        if (count($versionTokenList) >= 3) {
            $minor = $versionTokenList[2]->getValue();
        }

        if (count($versionTokenList) == 5) {
            $patch = $versionTokenList[4]->getValue();
        }

        if (count($labelTokenList)) {
            $label = $this->getLabelFromTokens($labelTokenList);
        }

        return new Version($major, $minor, $patch, $label);
    }

    /**
     * Get a Label instance from label tokens.
     *
     * @todo Handle build metadata
     *
     * @param Token[] $labelTokenList
     *
     * @return LabelInterface
     */
    private function getLabelFromTokens(array $labelTokenList)
    {
        $builder = $this->labelBuilder;

        if (count($labelTokenList)) {
            $builder = $builder->setName($labelTokenList[0]->getValue());

            if (3 === count($labelTokenList)) {
                $builder = $builder->setVersion($labelTokenList[2]->getValue());
            }
        }

        return $builder->build();
    }

    /**
     * Get Version & comparator instances from wildcard version tokens.
     *
     * @param Token[] $tokenList
     *
     * @return array Version and Comparator instances.
     */
    private function getWildcardVersionFromTokens(array $tokenList)
    {
        $resultList = array();

        // Minor wildcard
        if (3 === count($tokenList)) {
            $resultList[] = new GreaterOrEqualTo();
            $resultList[] = new Version(
                $tokenList[0]->getValue()
            );
            $resultList[] = new LessThan();
            $resultList[] = new Version(
                $tokenList[0]->getValue() + 1
            );

        // Patch wildcard
        } else {
            $resultList[] = new GreaterOrEqualTo();
            $resultList[] = new Version(
                $tokenList[0]->getValue(),
                $tokenList[2]->getValue()
            );
            $resultList[] = new LessThan();
            $resultList[] = new Version(
                $tokenList[0]->getValue(),
                $tokenList[2]->getValue() + 1
            );
        }

        return $resultList;
    }

    /**
     * Get Version & comparator instances from tilde range version tokens.
     *
     * @param Token[] $tokenList
     *
     * @return array Version and comparator instances.
     */
    private function getTildeVersionFromTokens(array $tokenList)
    {
        $resultList = array();

        // Upto Minor version
        if (3 === count($tokenList)) {
            $resultList[] = new GreaterOrEqualTo();
            $resultList[] = new Version(
                $tokenList[0]->getValue(),
                $tokenList[2]->getValue()
            );
            $resultList[] = new LessThan();
            $resultList[] = new Version(
                $tokenList[0]->getValue() + 1
            );

        // Upto Major version
        } else {
            $resultList[] = new GreaterOrEqualTo();
            $resultList[] = new Version(
                $tokenList[0]->getValue(),
                $tokenList[2]->getValue(),
                $tokenList[4]->getValue()
            );
            $resultList[] = new LessThan();
            $resultList[] = new Version(
                $tokenList[0]->getValue(),
                $tokenList[2]->getValue() + 1
            );
        }

        return $resultList;
    }

    /**
     * Get Version & comparator instances from caret range version tokens.
     *
     * @param Token[] $tokenList
     *
     * @return array Version and comparator instances.
     */
    private function getCaretVersionFromTokens(array $tokenList)
    {
        $resultList = array();

        $patch = 0;
        if (5 === count($tokenList)) {
            $patch = $tokenList[4]->getValue();
        }

        $resultList[] = new GreaterOrEqualTo();
        $resultList[] = new Version(
            $tokenList[0]->getValue(),
            $tokenList[2]->getValue(),
            $patch
        );
        $resultList[] = new LessThan();
        $resultList[] = new Version(
            $tokenList[0]->getValue() + 1
        );

        return $resultList;
    }

    /**
     * Get a comparator instance from comparator token.
     *
     * @param Token $token
     *
     * @return ComparatorInterface|null
     */
    private function getComparatorByTokenType(Token $token)
    {
        $comparatorMap = array(
            Token::GREATER_THAN => new GreaterThan(),
            Token::GREATER_THAN_EQUAL => new GreaterOrEqualTo(),
            Token::LESS_THAN => new LessThan(),
            Token::LESS_THAN_EQUAL => new LessOrEqualTo(),
            Token::EQUAL_TO => new EqualTo()
        );

        $comparator = null;
        if (array_key_exists($token->getType(), $comparatorMap)) {
            $comparator = $comparatorMap[$token->getType()];
        }

        return $comparator;
    }
}
