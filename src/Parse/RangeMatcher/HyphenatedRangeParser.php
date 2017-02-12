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

use ptlis\SemanticVersion\Comparator\ComparatorInterface;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\Version;
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
    use ChunkByDash;

    /** @var VersionParser */
    private $versionParser;

    /** @var ComparatorInterface */
    private $greaterOrEqualTo;

    /** @var ComparatorInterface */
    private $lessThan;

    /** @var ComparatorInterface */
    private $lessOrEqualTo;


    /**
     * Constructor.
     *
     * @param VersionParser $versionParser
     * @param ComparatorInterface $greaterOrEqualTo
     * @param ComparatorInterface $lessThan
     * @param ComparatorInterface $lessOrEqualTo
     */
    public function __construct(
        VersionParser $versionParser,
        ComparatorInterface $greaterOrEqualTo,
        ComparatorInterface $lessThan,
        ComparatorInterface $lessOrEqualTo
    ) {
        $this->versionParser = $versionParser;
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
        $validConfigurations = [
            [Token::DIGITS, Token::DIGITS],
            [Token::DIGITS, Token::LABEL_STRING, Token::DIGITS],
            [Token::DIGITS, Token::DIGITS, Token::LABEL_STRING],
            [Token::DIGITS, Token::LABEL_STRING, Token::DIGITS, Token::LABEL_STRING]
        ];

        $isRange = false;
        $chunkedList = $this->chunk($tokenList);
        foreach ($validConfigurations as $configuration) {
            $isRange = $isRange || $this->chunksMatchConfiguration($chunkedList, $configuration);
        }

        return $isRange;
    }

    /**
     * Returns true if the provided token
     *
     * @param Token[][] $chunkedList
     * @param string[] $configuration
     *
     * @return boolean
     */
    private function chunksMatchConfiguration(
        array $chunkedList,
        array $configuration
    ) {
        $matches = count($chunkedList) === count($configuration);

        foreach ($configuration as $index => $token) {
            if ($matches) {
                $matches = $chunkedList[$index][0]->getType() === $token;
            }
        }

        return $matches;
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
        if (!$this->canParse($tokenList)) {
            throw new \RuntimeException('Invalid version');
        }

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
        }

        return new LogicalAnd(
            $lowerVersionConstraint,
            $upperVersionConstraint
        );
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
        $tokenList = $versionTokenList;
        if (count($labelTokenList)) {
            $tokenList[] = new Token(Token::DASH_SEPARATOR, '-');
            $tokenList = array_merge($tokenList, $labelTokenList);
        }

        return new ComparatorVersion(
            $this->greaterOrEqualTo,
            $this->versionParser->parse($tokenList)
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
        $major = 0;
        $minor = 0;
        $patch = 0;
        $comparator = $this->lessThan;
        $labelBuilder = new LabelBuilder();

        switch (count($versionTokenList)) {
            case 1:
                $major = $versionTokenList[0]->getValue() + 1;
                break;

            case 3:
                $major = $versionTokenList[0]->getValue();
                $minor = $versionTokenList[2]->getValue() + 1;
                break;

            case 5:
                $comparator = $this->lessOrEqualTo;
                $major = $versionTokenList[0]->getValue();
                $minor = $versionTokenList[2]->getValue();
                $patch = $versionTokenList[4]->getValue();
                break;
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
