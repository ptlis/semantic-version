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
use ptlis\SemanticVersion\Version\Label\LabelAlpha;
use ptlis\SemanticVersion\Version\Label\LabelBeta;
use ptlis\SemanticVersion\Version\Label\LabelDev;
use ptlis\SemanticVersion\Version\Label\LabelInterface;
use ptlis\SemanticVersion\Version\Label\LabelRc;
use ptlis\SemanticVersion\Version\Version;

class VersionParser
{
    /**
     * Parse the token list, returning ...?
     *
     * @throws \RuntimeException
     *
     * @param Token[] $tokenList
     *
     * @return array of Versions and comparators
     */
    public function parse(array $tokenList)
    {
        $valueTypeList = array();

        for ($i = 0; $i < count($tokenList); $i++) {
            $currentToken = $tokenList[$i];

            switch (true) {

                // Beginning of version component
                case Token::DIGITS === $currentToken->getType():

                    $versionTokenList = $this->getMatchingTokens(
                        array_slice($tokenList, $i),
                        array(
                            Token::DOT_SEPARATOR,
                            Token::DIGITS,
                            Token::WILDCARD_DIGITS
                        )
                    );

                    if (!$this->validVersionTokens($versionTokenList)) {
                        throw new \RuntimeException('Invalid version string');
                    }

                    // Skip the number of tokens returned
                    $i += count($versionTokenList);

                    // Handle wildcard version
                    if (Token::WILDCARD_DIGITS === $versionTokenList[count($versionTokenList) - 1]->getType()) {

                        $valueTypeList = array_merge(
                            $valueTypeList,
                            $this->getWildcardVersionFromTokens($versionTokenList)
                        );

                    // Otherwise
                    } else {

                        // Now look for range or label separator
                        if ($i < count($tokenList) && Token::DASH_SEPARATOR === $tokenList[$i]->getType()) {

                            // Attempt to get a second token list
                            $secondVersionTokenList = $this->getMatchingTokens(
                                array_slice($tokenList, $i + 1),
                                array(
                                    Token::DOT_SEPARATOR,
                                    Token::DIGITS
                                )
                            );

                            // If tokens are returned then it's a range separator
                            if (count($secondVersionTokenList)) {
                                $valueTypeList[] = new GreaterOrEqualTo();
                                $valueTypeList[] = $this->getVersionFromTokens($versionTokenList);
                                $valueTypeList[] = new LessOrEqualTo();
                                $valueTypeList[] = $this->getVersionFromTokens($secondVersionTokenList);

                                // Skip the number of tokens returned
                                $i += count($secondVersionTokenList) + 1;

                            // If no tokens are returned then it's a label separator
                            } else {
                                // Get label token list
                                $labelTokenList = $this->getMatchingTokens(
                                    array_slice($tokenList, $i + 1),
                                    array(
                                        Token::DOT_SEPARATOR,
                                        Token::DIGITS,
                                        Token::LABEL_STRING
                                    )
                                );


                                $valueTypeList[] = $this->getVersionFromTokens($versionTokenList, $labelTokenList);

                                // Skip the number of tokens returned
                                $i += count($labelTokenList) + 1;

                                // TODO: Handle build metadata
                            }

                        // Simple version number
                        } else {
                            $valueTypeList[] = $this->getVersionFromTokens($versionTokenList);
                        }
                    }
                    break;

                // Is comparator
                case !is_null($this->getComparatorByTokenType($currentToken)):

                    $versionTokenList = $this->getMatchingTokens(
                        array_slice($tokenList, $i + 1),
                        array(
                            Token::DOT_SEPARATOR,
                            Token::DIGITS
                        )
                    );

                    if (!$this->validVersionTokens($versionTokenList)) {
                        throw new \RuntimeException('Invalid version string');
                    }

                    $valueTypeList[] = $this->getComparatorByTokenType($currentToken);
                    $valueTypeList[] = $this->getVersionFromTokens($versionTokenList);

                    // Skip the number of version tokens returned
                    $i += count($versionTokenList); // TODO: +1 ?
                    break;

                // Beginning of tilde range
                case Token::TILDE_RANGE === $currentToken->getType():

                    $versionTokenList = $this->getMatchingTokens(
                        array_slice($tokenList, $i + 1),
                        array(
                            Token::DOT_SEPARATOR,
                            Token::DIGITS
                        )
                    );

                    if (!$this->validVersionTokens($versionTokenList)) {
                        throw new \RuntimeException('Invalid version string');
                    }

                    $valueTypeList = array_merge(
                        $valueTypeList,
                        $this->getTildeVersionFromTokens($versionTokenList)
                    );

                    // Skip the number of version tokens returned
                    $i += count($versionTokenList); // TODO: +1 ?
                    break;

                // Beginning of caret range
                case Token::CARET_RANGE === $currentToken->getType():

                    $versionTokenList = $this->getMatchingTokens(
                        array_slice($tokenList, $i + 1),
                        array(
                            Token::DOT_SEPARATOR,
                            Token::DIGITS
                        )
                    );

                    $valueTypeList = array_merge(
                        $valueTypeList,
                        $this->getCaretVersionFromTokens($versionTokenList)
                    );

                    // Skip the number of version tokens returned
                    $i += count($versionTokenList); // TODO: +1 ?
                    break;
            }
        }

       return $valueTypeList;
    }

    /**
     * Get an array of tokens matching the provided list.
     *
     * @param Token[] $tokenList
     * @param string[] $tokensToMatch
     *
     * @return Token[]
     */
    private function getMatchingTokens(array $tokenList, array $tokensToMatch)
    {
        $matchingTokenList = array();

        foreach ($tokenList as $token) {
            if (in_array($token->getType(), $tokensToMatch)) {
                $matchingTokenList[] = $token;
            } else {
                break;
            }
        }

        return $matchingTokenList;
    }

    /**
     * Returns true version tokens are in a valid configuration.
     *
     * @param Token[] $tokenList
     *
     * @return bool
     */
    private function validVersionTokens(array $tokenList)
    {
        $valid = true;

        // Odd numbers only, token list must be less no greater than 5 (3 digit tokens separated by 2 dots)
        if (0 !== (count($tokenList) % 2) && count($tokenList) <= 5) {
            $lastToken = Token::DOT_SEPARATOR;
            for ($i = 0; $i < count($tokenList); $i++) {

                if (!$this->validSubsequentVersionToken($lastToken, $tokenList[$i]->getType())) {
                    $valid = false;
                    break;
                }

                $lastToken = $tokenList[$i]->getType();
            }
        } else {
            $valid = false;
        }

        return $valid;
    }

    /**
     * Returns true if the current token is allowed following the last token.
     *
     * @param string $lastToken
     * @param string $currentToken
     *
     * @return bool
     */
    private function validSubsequentVersionToken($lastToken, $currentToken)
    {
        $digitTokenList = array(
            Token::DIGITS,
            Token::WILDCARD_DIGITS
        );

        return (Token::DOT_SEPARATOR === $lastToken && in_array($currentToken, $digitTokenList))
            || (in_array($lastToken, $digitTokenList) && Token::DOT_SEPARATOR === $currentToken);
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
     * @param Token[] $labelTokenList
     *
     * @return LabelInterface
     */
    private function getLabelFromTokens(array $labelTokenList)
    {
        $name = $labelTokenList[0]->getValue();
        $version = null;
        if (3 === count($labelTokenList)) {
            $version = $labelTokenList[2]->getValue();
        }

        // TODO: move to builder
        $label = null;
        switch ($name) {
            case 'alpha':
                $label = new LabelAlpha($version);
                break;
            case 'beta':
                $label = new LabelBeta($version);
                break;
            case 'rc':
                $label = new LabelRc($version);
                break;
            default:
                $label = new LabelDev($name, $version);
                break;
        }

        return $label;
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
