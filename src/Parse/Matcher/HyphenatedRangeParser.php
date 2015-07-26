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

use ptlis\SemanticVersion\Comparator\ComparatorInterface;
use ptlis\SemanticVersion\Parse\Token;
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
     * @param ComparatorInterface $greaterOrEqualTo
     * @param ComparatorInterface $lessThan
     * @param ComparatorInterface $lessOrEqualTo
     */
    public function __construct(
        ComparatorInterface $greaterOrEqualTo,
        ComparatorInterface $lessThan,
        ComparatorInterface $lessOrEqualTo
    ) {
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
     * Determines the correct lower version constraint for a hyphenated range.
     *
     * @param Token[] $versionTokenList
     * @param Token[] $labelTokenList
     *
     * @return VersionRangeInterface
     */
    private function getLowerConstraint(array $versionTokenList, array $labelTokenList = array())
    {
        $versionParser = new ComparatorVersionParser(); // TODO: Inject!

        return new ComparatorVersion(
            $this->greaterOrEqualTo,
            $versionParser->parseVersion($versionTokenList, $labelTokenList)
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
    private function getUpperConstraint(array $versionTokenList, array $labelTokenList = array())
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

        // TODO: This part is copied & pasted from ComparatorVersionParser::parseVersion!
        switch (count($labelTokenList)) {

            // No label
            case 0:
                // Do Nothing
                break;

            // Version string part only
            case 1:
                $label = $labelBuilder
                    ->setName($labelTokenList[0]->getValue())
                    ->build();
                break;

            // Label version
            case 3:
                $label = $labelBuilder
                    ->setName($labelTokenList[0]->getValue())
                    ->setVersion($labelTokenList[2]->getValue())
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
