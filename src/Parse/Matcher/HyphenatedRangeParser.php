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

use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Version\Label\Label;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Parser for hyphenated ranges.
 *
 * Hyphenated ranges are implemented as described @ https://getcomposer.org/doc/01-basic-usage.md#package-versions
 */
class HyphenatedRangeParser implements RangeParserInterface
{
    /**
     * Returns true if the token list can be parsed as a hyphenated range.
     *
     * @param Token[] $tokenList
     *
     * @return boolean
     */
    public function canParse(array $tokenList)
    {
        $isRange = false;

        for ($i = 0; $i < count($tokenList); $i++) {
            $token = $tokenList[$i];
            if (
                Token::DASH_SEPARATOR === $token->getType()
                && $i + 1 < count($tokenList)
                && Token::LABEL_STRING !== $tokenList[$i]
            ) {
                $isRange = true;
            }
        }

        return $isRange;
    }

    /**
     * Build a ComparatorVersion representing the hyphenated range.
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function parse(array $tokenList)
    {
        $chunkedList = $this->chunk($tokenList);
        $versionParser = new ComparatorVersionParser();

        switch (count($chunkedList)) {

            // No labels
            case 2:
                $lowerVersion = $versionParser->parseVersion($chunkedList[0]);
                $upperVersionConstraint = $this->getUpperVersionConstraintFromTokens($chunkedList[1]);
                break;

            // Label on one version
            case 3:
                // Label belongs to first version
                if (Token::LABEL_STRING === $chunkedList[1][0]->getType()) {
                    $lowerMerged = array_merge(
                        $chunkedList[0],
                        array(new Token(Token::DASH_SEPARATOR, '-')),
                        $chunkedList[1]
                    );
                    $lowerVersion = $versionParser->parseVersion($lowerMerged);

                    $upperVersionConstraint = $this->getUpperVersionConstraintFromTokens($chunkedList[2]);

                // Label belongs to second version
                } else {
                    $lowerVersion = $versionParser->parseVersion($chunkedList[0]);

                    $upperMerged = array_merge(
                        $chunkedList[1],
                        array(new Token(Token::DASH_SEPARATOR, '-')),
                        $chunkedList[2]
                    );
                    $upperVersionConstraint = $this->getUpperVersionConstraintFromTokens($upperMerged);
                }

                break;

            // Label on both versions
            case 4:
                $lowerMerged = array_merge(
                    $chunkedList[0],
                    array(new Token(Token::DASH_SEPARATOR, '-')),
                    $chunkedList[1]
                );
                $upperMerged = array_merge(
                    $chunkedList[2],
                    array(new Token(Token::DASH_SEPARATOR, '-')),
                    $chunkedList[3]
                );

                $lowerVersion = $versionParser->parseVersion($lowerMerged);
                $upperVersionConstraint = $this->getUpperVersionConstraintFromTokens($upperMerged);
                break;

            default:
                throw new \RuntimeException('Invalid version');
        }

        return new LogicalAnd(
            new ComparatorVersion(
                new GreaterOrEqualTo(),
                $lowerVersion
            ),
            $upperVersionConstraint
        );
    }

    /**
     * Chuck the tokens, splitting on hyphen.
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
     * Determines the correct upper version constraint for a hyphenated range.
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    private function getUpperVersionConstraintFromTokens(array $tokenList)
    {
        $minor = 0;
        $patch = 0;
        $label = null;
        $labelBuilder = new LabelBuilder();

        switch (count($tokenList)) {
            case 1:
                $comparator = new LessThan();
                $major = $tokenList[0]->getValue() + 1;
                break;

            case 3:
                $comparator = new LessThan();
                $major = $tokenList[0]->getValue();
                $minor = $tokenList[2]->getValue() + 1;
                break;

            case 5:
                $comparator = new LessOrEqualTo();
                $major = $tokenList[0]->getValue();
                $minor = $tokenList[2]->getValue();
                $patch = $tokenList[4]->getValue();
                break;

            case 7:
                $comparator = new LessOrEqualTo();
                $major = $tokenList[0]->getValue();
                $minor = $tokenList[2]->getValue();
                $patch = $tokenList[4]->getValue();
                $label = $labelBuilder
                    ->setName($tokenList[6]->getValue())
                    ->build();
                break;

            case 9:
                $comparator = new LessOrEqualTo();
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

        return new ComparatorVersion(
            $comparator,
            new Version(
                $major,
                $minor,
                $patch,
                $label
            )
        );
    }
}
