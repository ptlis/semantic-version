<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Parse\RangeParser;

use ptlis\SemanticVersion\Comparator\ComparatorInterface;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\Version\Version;

/**
 * Parser for caret ranges.
 *
 * Behaviour of caret ranges is described @ https://getcomposer.org/doc/articles/versions.md#caret
 */
final class CaretRangeParser implements RangeParserInterface
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
     * Returns true if the provided tokens represent a caret range.
     *
     * @param Token[] $tokenList
     *
     * @return boolean
     */
    public function canParse(array $tokenList)
    {
        return (
            count($tokenList) > 0
            && Token::CARET_RANGE === $tokenList[0]->getType()
            && $this->versionParser->canParse(array_slice($tokenList, 1))
        );
    }

    /**
     * Build a ComparatorVersion representing the caret range.
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function parse(array $tokenList)
    {
        if (!$this->canParse($tokenList)) {
            throw new \RuntimeException('Invalid caret range (^) version range');
        }

        $lowerVersion = $this->versionParser->parse(array_slice($tokenList, 1));

        return new LogicalAnd(
            new ComparatorVersion(
                $this->greaterOrEqualTo,
                $lowerVersion
            ),
            new ComparatorVersion(
                $this->lessThan,
                new Version($lowerVersion->getMajor() + 1)
            )
        );
    }
}
