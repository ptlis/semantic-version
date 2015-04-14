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
     * Constructor.
     *
     * @param LabelBuilder $labelBuilder
     */
    public function __construct(LabelBuilder $labelBuilder)
    {
        $this->labelBuilder = $labelBuilder;
        $this->comparatorFactory = new ComparatorFactory(); // TODO: Inject
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
        $buildRange = new BuildRange();

        return $buildRange->run($realResultList);
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
    private function getUpperVersionForHyphenRange(array $tokenList, array $labelTokenList = array())
    {
        $minor = 0;
        $patch = 0;

        switch (count($tokenList)) {
            case 1:
                $comparator = $this->comparatorFactory->get('<');
                $major = $tokenList[0]->getValue() + 1;
                break;

            case 3:
                $comparator = $this->comparatorFactory->get('<');
                $major = $tokenList[0]->getValue();
                $minor = $tokenList[2]->getValue() + 1;
                break;

            case 5:
                $comparator = $this->comparatorFactory->get('<=');
                $major = $tokenList[0]->getValue();
                $minor = $tokenList[2]->getValue();
                $patch = $tokenList[4]->getValue();
                break;

            default:
                throw new \RuntimeException('Invalid version'); // TODO: Handle earlier in validation step
                break;
        }

        return array(
            $comparator,
            new Version(
                $major,
                $minor,
                $patch,
                $this->getLabelFromTokens($labelTokenList)
            )
        );
    }


    /**
     * @param Token[] $tokenList
     */
    private function parseHyphenated($tokenList)
    {
        $chunkedList = $this->chunkOnHyphen($tokenList);

        $resultList = array();

        switch (count($chunkedList)) {
            // Simple range or version with label
            case 2:

                // Version with label
                if (Token::LABEL_STRING === $chunkedList[1][0]->getType()) {
                    $resultList[] = $this->comparatorFactory->get('=');
                    $resultList[] = $this->getVersionFromTokens($chunkedList[0], $chunkedList[1]);

                // Version range
                } else {
                    $resultList[] = $this->comparatorFactory->get('>=');
                    $resultList[] = $this->getVersionFromTokens($chunkedList[0]);
                    $resultList[] = new Token(Token::LOGICAL_AND, '');
                    $resultList = array_merge(
                        $resultList,
                        $this->getUpperVersionForHyphenRange($chunkedList[1])
                    );
                }

                break;

            // Range where one version has label
            case 3:
                // Label belongs to left version
                if (Token::LABEL_STRING === $chunkedList[1][0]->getType()) {
                    $resultList[] = $this->comparatorFactory->get('>=');
                    $resultList[] = $this->getVersionFromTokens($chunkedList[0], $chunkedList[1]);
                    $resultList[] = new Token(Token::LOGICAL_AND, '');
                    $resultList = array_merge(
                        $resultList,
                        $this->getUpperVersionForHyphenRange($chunkedList[2])
                    );

                // Label belongs to right version
                } else {
                    $resultList[] = $this->comparatorFactory->get('>=');
                    $resultList[] = $this->getVersionFromTokens($chunkedList[0]);
                    $resultList[] = new Token(Token::LOGICAL_AND, '');
                    $resultList = array_merge(
                        $resultList,
                        $this->getUpperVersionForHyphenRange($chunkedList[1], $chunkedList[2])
                    );
                }

                break;

            // Range where both versions have label
            case 4:
                $resultList[] = $this->comparatorFactory->get('>=');
                $resultList[] = $this->getVersionFromTokens($chunkedList[0], $chunkedList[1]);
                $resultList[] = new Token(Token::LOGICAL_AND, '');
                $resultList = array_merge(
                    $resultList,
                    $this->getUpperVersionForHyphenRange($chunkedList[2], $chunkedList[3])
                );
                break;
        }

        return $resultList;
    }

    /**
     * @param Token[] $tokenList
     *
     * @return Token[][]
     */
    private function chunkOnHyphen($tokenList)
    {
        $chunkedTokenList = array();

        $index = 0;
        foreach ($tokenList as $token) {
            if (Token::DASH_SEPARATOR === $token->getType()) {
                $index++;
            } else {
                $chunkedTokenList[$index][] = $token;
            }
        }

        return $chunkedTokenList;
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

        $that = $this;
        $addClusteredTokens = function($accumulatedTokenList) use (&$tokenClusterList, $that) {
            $accumulatedTokenCount = count($accumulatedTokenList);

            if ($accumulatedTokenCount) {

                switch (true) {
                    case !is_null($comparator = $that->comparatorFactory->getFromToken($accumulatedTokenList[0])):
                        $tokenClusterList[] = $comparator;
                        break;

                    case Token::DIGITS === $accumulatedTokenList[0]->getType():

                        $hyphenated = false;

                        for ($i = 0; $i < count($accumulatedTokenList); $i++) {
                            if (Token::DASH_SEPARATOR === $accumulatedTokenList[$i]->getType()) {
                                $hyphenated = true;
                                break;
                            }
                        }

                        if ($hyphenated) {
                            $tokenClusterList = array_merge(
                                $tokenClusterList,
                                $that->parseHyphenated($accumulatedTokenList)
                            );

                        } elseif (Token::WILDCARD_DIGITS === $accumulatedTokenList[$accumulatedTokenCount - 1]->getType()) {
                            $tokenClusterList = array_merge(
                                $tokenClusterList,
                                $that->getWildcardVersionFromTokens($accumulatedTokenList)
                            );

                        } else {
                            $tokenClusterList[] = $that->getVersionFromTokens($accumulatedTokenList);
                        }

                        break;

                    case Token::TILDE_RANGE === $accumulatedTokenList[0]->getType():
                        $tokenClusterList = array_merge(
                            $tokenClusterList,
                            $that->getTildeVersionFromTokens(array_slice($accumulatedTokenList, 1))
                        );
                        break;

                    case Token::CARET_RANGE === $accumulatedTokenList[0]->getType():
                        $tokenClusterList = array_merge(
                            $tokenClusterList,
                            $that->getCaretVersionFromTokens(array_slice($accumulatedTokenList, 1))
                        );
                        break;

                    default:
                        $tokenClusterList[] = $accumulatedTokenList[0];
                        break;
                }
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

                // Beginning caret or tilde range
                case in_array($currentToken->getType(), array(Token::TILDE_RANGE, Token::CARET_RANGE)):
                    $addClusteredTokens($tokenAccumulator);
                    $tokenAccumulator = array();
                    $tokenAccumulator[] = $currentToken;
                    break;

                // Comparator or logical operator
                case !is_null($that->comparatorFactory->getFromToken($currentToken)):
                case in_array($currentToken->getType(), array(Token::LOGICAL_AND, Token::LOGICAL_OR)):
                    $addClusteredTokens($tokenAccumulator);
                    $addClusteredTokens(array($currentToken));
                    $tokenAccumulator = array();
                    break;

                // Any other case simply accumulate the token
                default:
                    $tokenAccumulator[] = $currentToken;
                    break;
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
            $resultList[] = $this->comparatorFactory->get('>=');
            $resultList[] = new Version(
                $tokenList[0]->getValue()
            );
            // Fake token (TODO: Concrete type?)
            $resultList[] = new Token(Token::LOGICAL_AND, '');
            $resultList[] = $this->comparatorFactory->get('<');
            $resultList[] = new Version(
                $tokenList[0]->getValue() + 1
            );

        // Patch wildcard
        } else {
            $resultList[] = $this->comparatorFactory->get('>=');
            $resultList[] = new Version(
                $tokenList[0]->getValue(),
                $tokenList[2]->getValue()
            );
            // Fake token (TODO: Concrete type?)
            $resultList[] = new Token(Token::LOGICAL_AND, '');
            $resultList[] = $this->comparatorFactory->get('<');
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
            $resultList[] = $this->comparatorFactory->get('>=');
            $resultList[] = new Version(
                $tokenList[0]->getValue(),
                $tokenList[2]->getValue()
            );
            // Fake token (TODO: Concrete type?)
            $resultList[] = new Token(Token::LOGICAL_AND, '');
            $resultList[] = $this->comparatorFactory->get('<');
            $resultList[] = new Version(
                $tokenList[0]->getValue() + 1
            );

        // Upto Major version
        } else {
            $resultList[] = $this->comparatorFactory->get('>=');
            $resultList[] = new Version(
                $tokenList[0]->getValue(),
                $tokenList[2]->getValue(),
                $tokenList[4]->getValue()
            );
            // Fake token (TODO: Concrete type?)
            $resultList[] = new Token(Token::LOGICAL_AND, '');
            $resultList[] = $this->comparatorFactory->get('<');
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

        $resultList[] = $this->comparatorFactory->get('>=');
        $resultList[] = new Version(
            $tokenList[0]->getValue(),
            $tokenList[2]->getValue(),
            $patch
        );
        // Fake token (TODO: Concrete type?)
        $resultList[] = new Token(Token::LOGICAL_AND, '');
        $resultList[] = $this->comparatorFactory->get('<');
        $resultList[] = new Version(
            $tokenList[0]->getValue() + 1
        );

        return $resultList;
    }
}
