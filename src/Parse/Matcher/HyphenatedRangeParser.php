<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Parse\Matcher;

use ptlis\SemanticVersion\Comparator\ComparatorInterface;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\Version\VersionBuilder;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Parser for hyphenated ranges.
 *
 * Hyphenated ranges are implemented as described @ https://getcomposer.org/doc/articles/versions.md#range-hyphen-
 */
final class HyphenatedRangeParser implements RangeParserInterface
{
    /**
     * @var VersionBuilder
     */
    private $versionBuilder;

    /**
     * @var ComparatorInterface
     */
    private $greaterOrEqualTo;

    /**
     * @var ComparatorInterface
     */
    private $lessThan;

    /**
     * @var ComparatorInterface
     */
    private $lessOrEqualTo;


    /**
     * Constructor.
     *
     * @param VersionBuilder $versionBuilder
     * @param ComparatorInterface $greaterOrEqualTo
     * @param ComparatorInterface $lessThan
     * @param ComparatorInterface $lessOrEqualTo
     */
    public function __construct(
        VersionBuilder $versionBuilder,
        ComparatorInterface $greaterOrEqualTo,
        ComparatorInterface $lessThan,
        ComparatorInterface $lessOrEqualTo
    ) {
        $this->versionBuilder = $versionBuilder;
        $this->greaterOrEqualTo = $greaterOrEqualTo;
        $this->lessThan = $lessThan;
        $this->lessOrEqualTo = $lessOrEqualTo;
    }

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

        switch (count($chunkedList)) {

            // No labels
            case 2:
                $lowerVersionConstraint = $this->getLowerConstraint($chunkedList[0]);
                $upperVersionConstraint = $this->getUpperConstraint($chunkedList[1]);
                break;

            // Label on one version
            case 3:
                // Label belongs to first version
                if (Token::LABEL_STRING === $chunkedList[1][0]->getType()) {
                    $lowerVersionConstraint = $this->getLowerConstraint($chunkedList[0], $chunkedList[1]);
                    $upperVersionConstraint = $this->getUpperConstraint($chunkedList[2]);

                // Label belongs to second version
                } else {
                    $lowerVersionConstraint = $this->getLowerConstraint($chunkedList[0]);
                    $upperVersionConstraint = $this->getUpperConstraint($chunkedList[1], $chunkedList[2]);
                }

                break;

            // Label on both versions
            case 4:
                $lowerVersionConstraint = $this->getLowerConstraint($chunkedList[0], $chunkedList[1]);
                $upperVersionConstraint = $this->getUpperConstraint($chunkedList[2], $chunkedList[3]);
                break;

            default:
                throw new \RuntimeException('Invalid version');
        }

        return new LogicalAnd(
            $lowerVersionConstraint,
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
        $chunkedList = [];
        $accumulator = [];

        for ($i = 0; $i < $tokenListCount; $i++) {
            $token = $tokenList[$i];

            // Accumulate until we hit a dash
            if (Token::DASH_SEPARATOR !== $token->getType()) {
                $accumulator[] = $token;

            } else {
                $chunkedList[] = $accumulator;
                $accumulator = [];
            }
        }

        if (count($accumulator)) {
            $chunkedList[] = $accumulator;
        }

        return $chunkedList;
    }

    /**
     * Determines the correct lower version constraint for a hyphenated range.
     *
     * @param Token[] $versionTokenList
     * @param Token[] $labelTokenList
     *
     * @return VersionRangeInterface
     */
    private function getLowerConstraint(array $versionTokenList, array $labelTokenList = [])
    {
        return new ComparatorVersion(
            $this->greaterOrEqualTo,
            $this->versionBuilder->buildFromTokens($versionTokenList, $labelTokenList)
        );
    }

    /**
     * Determines the correct upper version constraint for a hyphenated range.
     *
     * @param Token[] $versionTokenList
     * @param Token[] $labelTokenList
     *
     * @return VersionRangeInterface
     */
    private function getUpperConstraint(array $versionTokenList, array $labelTokenList = [])
    {
        $minor = 0;
        $patch = 0;
        $label = null;
        $labelBuilder = new LabelBuilder();

        switch (count($versionTokenList)) {
            case 1:
                $comparator = $this->lessThan;
                $major = $versionTokenList[0]->getValue() + 1;
                break;

            case 3:
                $comparator = $this->lessThan;
                $major = $versionTokenList[0]->getValue();
                $minor = $versionTokenList[2]->getValue() + 1;
                break;

            case 5:
                $comparator = $this->lessOrEqualTo;
                $major = $versionTokenList[0]->getValue();
                $minor = $versionTokenList[2]->getValue();
                $patch = $versionTokenList[4]->getValue();
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
                $labelBuilder->buildFromTokens($labelTokenList)
            )
        );
    }
}
