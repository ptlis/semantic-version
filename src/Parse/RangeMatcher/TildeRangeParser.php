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
 * Parser for tilde ranges.
 *
 * Behaviour of caret ranges is described @ https://getcomposer.org/doc/articles/versions.md#tilde
 */
final class TildeRangeParser implements RangeParserInterface
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
     * Returns true if the provided tokens represent a tilde range.
     *
     * @param Token[] $tokenList
     *
     * @return boolean
     */
    public function canParse(array $tokenList)
    {
        return (
            count($tokenList) > 0
            && Token::TILDE_RANGE === $tokenList[0]->getType()
            && $this->versionParser->canParse(array_slice($tokenList, 1))
        );
    }

    /**
     * Build a comparator version representing the tilde range.
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

        // Remove prefix tilde
        $tokenList = array_slice($tokenList, 1);

        $lowerVersion = $this->versionParser->parse($tokenList);

        // Upto minor version
        if (3 === count($tokenList)) {
            $upperVersion = new Version($lowerVersion->getMajor() + 1);

            // Upto patch version
        } else {
            $upperVersion = new Version($lowerVersion->getMajor(), $lowerVersion->getMinor() + 1);
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
