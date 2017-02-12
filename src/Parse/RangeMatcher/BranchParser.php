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
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Parser for composer branch ranges.
 *
 * Behaviour of branch ranges is described @ https://getcomposer.org/doc/02-libraries.md#branches
 */
final class BranchParser implements RangeParserInterface
{
    /** @var VersionParser */
    private $versionParser;

    /** @var ComparatorInterface */
    private $greaterOrEqualTo;

    /** @var ComparatorInterface */
    private $lessThan;


    /**
     * Constructor.
     *
     * @param VersionParser $versionParser
     * @param ComparatorInterface $greaterOrEqualTo
     * @param ComparatorInterface $lessThan
     */
    public function __construct(
        VersionParser $versionParser,
        ComparatorInterface $greaterOrEqualTo,
        ComparatorInterface $lessThan
    ) {
        $this->versionParser = $versionParser;
        $this->greaterOrEqualTo = $greaterOrEqualTo;
        $this->lessThan = $lessThan;
    }

    /**
     * Returns true if the tokens can be parsed as a Packagist-style branch
     *
     * @param Token[] $tokenList
     *
     * @return boolean
     */
    public function canParse(array $tokenList)
    {
        $tokenListCount = count($tokenList);

        return (
            $tokenListCount >= 3
            && Token::WILDCARD_DIGITS === $tokenList[$tokenListCount - 3]->getType()
            && Token::DASH_SEPARATOR === $tokenList[$tokenListCount - 2]->getType()
            && Token::LABEL_STRING === $tokenList[$tokenListCount - 1]->getType()
        );
    }

    /**
     * Build a ComparatorVersion representing the branch.
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function parse(array $tokenList)
    {
        // Upto minor version
        if (3 === count($tokenList)) {
            $lowerVersion = new Version($tokenList[0]->getValue(), $tokenList[2]->getValue());
            $upperVersion = new Version($tokenList[0]->getValue() + 1);

            // Upto patch version
        } else {
            $lowerVersion = new Version($tokenList[0]->getValue(), $tokenList[2]->getValue(), $tokenList[4]->getValue());
            $upperVersion = new Version($tokenList[0]->getValue(), $tokenList[2]->getValue() + 1);
        }

        return new LogicalAnd(
            new ComparatorVersion(
                $this->greaterOrEqualTo,
                $lowerVersion
            ),
            new ComparatorVersion(
                $this->lessThan,
                $upperVersion
            )
        );
    }
}
