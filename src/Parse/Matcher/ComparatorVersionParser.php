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

use ptlis\SemanticVersion\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Comparator versions store a comparator & version specifying part of a version range.
 */
class ComparatorVersionParser implements RangeParserInterface
{
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
                1 === count($chunkedList)
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
        $comparatorList = array(
            '<',
            '<=',
            '>',
            '>=',
            '='
        );

        $comparatorFactory = new ComparatorFactory();

        // Prefixed comparator, hydrate & remove
        if (count($tokenList) > 0 && in_array($tokenList[0]->getValue(), $comparatorList)) {
            $comparator = $comparatorFactory->get($tokenList[0]->getValue());
            $tokenList = array_slice($tokenList, 1);

        // Default to equality
        } else {
            $comparator = $comparatorFactory->get('=');
        }

        return new ComparatorVersion(
            $comparator,
            $this->parseVersion($tokenList)
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
        $illegalTokenList = array(
            Token::CARET_RANGE,
            Token::TILDE_RANGE,
            Token::WILDCARD_DIGITS,
            Token::LOGICAL_AND,
            Token::LOGICAL_OR
        );

        $hasIllegalToken = false;
        foreach ($tokenList as $token) {
            if (in_array($token->getType(), $illegalTokenList)) {
                $hasIllegalToken = true;
            }
        }

        return $hasIllegalToken;
    }

    /**
     * Chuck the tokens, splitting on hyphen.
     *
     * @todo Copy & pasted from hyphenated range parser
     *
     * @param Token[] $tokenList
     *
     * @return Token[][]
     */
    private function chunk(array $tokenList)
    {
        $tokenListCount = count($tokenList);
        $chunkedList = array();
        $accumulator = array();

        for ($i = 0; $i < $tokenListCount; $i++) {
            $token = $tokenList[$i];

            // Accumulate until we hit a dash
            if (Token::DASH_SEPARATOR !== $token->getType()) {
                $accumulator[] = $token;

            } else {
                $chunkedList[] = $accumulator;
                $accumulator = array();
            }
        }

        if (count($accumulator)) {
            $chunkedList[] = $accumulator;
        }

        return $chunkedList;
    }

    /**
     * Parse a token list into a Version.
     *
     * @param Token[] $tokenList
     *
     * @return Version
     */
    public function parseVersion(array $tokenList)
    {
        $minor = 0;
        $patch = 0;
        $label = null;
        $labelBuilder = new LabelBuilder();

        switch (count($tokenList)) {

            // Major Only
            case 1:
                $major = $tokenList[0]->getValue();
                break;

            // Major, minor
            case 3:
                $major = $tokenList[0]->getValue();
                $minor = $tokenList[2]->getValue();
                break;

            // Major, minor, patch
            case 5:
                $major = $tokenList[0]->getValue();
                $minor = $tokenList[2]->getValue();
                $patch = $tokenList[4]->getValue();
                break;

            // Major, minor, patch, label
            case 7:
                $major = $tokenList[0]->getValue();
                $minor = $tokenList[2]->getValue();
                $patch = $tokenList[4]->getValue();
                $label = $labelBuilder
                    ->setName($tokenList[6]->getValue())
                    ->build();
                break;

            // Major, minor, patch, label, label version
            case 9:
                $major = $tokenList[0]->getValue();
                $minor = $tokenList[2]->getValue();
                $patch = $tokenList[4]->getValue();
                $label = $labelBuilder
                    ->setName($tokenList[6]->getValue())
                    ->setVersion($tokenList[8]->getValue())
                    ->build();
                break;

            default:
                throw new \RuntimeException('Invalid version');
        }

        return new Version($major, $minor, $patch, $label);
    }
}
