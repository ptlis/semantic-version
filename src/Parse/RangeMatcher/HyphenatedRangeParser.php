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

    /** @var Token[][] */
    private $validConfigurations = [
        [Token::DIGITS, Token::DIGITS],
        [Token::DIGITS, Token::LABEL_STRING, Token::DIGITS],
        [Token::DIGITS, Token::DIGITS, Token::LABEL_STRING],
        [Token::DIGITS, Token::LABEL_STRING, Token::DIGITS, Token::LABEL_STRING]
    ];


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
        $isRange = false;
        $chunkedList = $this->chunk($tokenList);
        foreach ($this->validConfigurations as $configuration) {
            list($lowerVersionTokenList, $upperVersionTokenList) = $this->getSingleVersionTokens($chunkedList);
            $isRange = $isRange || (
                $this->chunksMatchConfiguration($chunkedList, $configuration)
                && $this->versionParser->parse($lowerVersionTokenList)
                && $this->versionParser->parse($upperVersionTokenList)
            );
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
        if (!$this->canParse($tokenList)) {
            throw new \RuntimeException('Invalid version');
        }

        $chunkedList = $this->chunk($tokenList);

        list($lowerVersionTokenList, $upperVersionTokenList) = $this->getSingleVersionTokens($chunkedList);

        return new LogicalAnd(
            new ComparatorVersion(
                $this->greaterOrEqualTo,
                $this->versionParser->parse($lowerVersionTokenList)
            ),
            $this->getUpperConstraint($upperVersionTokenList)
        );
    }

    /**
     * Returns an array of token arrays, the first is tokens for the lower bound and the second is the upper bound.
     *
     * @param Token[][] $chunkedList
     *
     * @return Token[][]
     */
    private function getSingleVersionTokens(
        array $chunkedList
    ) {
        $dashToken = new Token(Token::DASH_SEPARATOR, '-');
        $lowerVersionTokenList = [];
        $upperVersionTokenList = [];
        switch (count($chunkedList)) {

            // No labels
            case 2:
                $lowerVersionTokenList = $chunkedList[0];
                $upperVersionTokenList = $chunkedList[1];
                break;

            // Label on one version
            case 3:
                // Label belongs to first version
                if (Token::LABEL_STRING === $chunkedList[1][0]->getType()) {
                    $lowerVersionTokenList = array_merge($chunkedList[0], [$dashToken], $chunkedList[1]);
                    $upperVersionTokenList = $chunkedList[2];

                    // Label belongs to second version
                } else {
                    $lowerVersionTokenList = $chunkedList[0];
                    $upperVersionTokenList = array_merge($chunkedList[1], [$dashToken], $chunkedList[2]);
                }

                break;

            // Label on both versions
            case 4:
                $lowerVersionTokenList = array_merge($chunkedList[0], [$dashToken], $chunkedList[1]);
                $upperVersionTokenList = array_merge($chunkedList[2], [$dashToken], $chunkedList[3]);
                break;
        }

        return [$lowerVersionTokenList, $upperVersionTokenList];
    }

    /**
     * Returns true if the chunks match the configuration.
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
     * Get the upper version constraint from a token list.
     *
     * @param Token[] $tokenList
     *
     * @return ComparatorVersion
     */
    private function getUpperConstraint(array $tokenList)
    {
        $comparator = $this->lessThan;
        $version = $this->versionParser->parse($tokenList);

        switch (true) {
            case 1 === count($tokenList):
                $version = new Version($version->getMajor() + 1, 0, 0);
                break;

            case 3 === count($tokenList):
                $version = new Version($version->getMajor(), $version->getMinor() + 1, 0);
                break;

            case count($tokenList) >= 5:
                $comparator = $this->lessOrEqualTo;
                break;
        }

        return new ComparatorVersion($comparator, $version);
    }
}
