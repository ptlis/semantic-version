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

/**
 * Parser for tilde ranges.
 *
 * Behaviour of caret ranges is described @ https://getcomposer.org/doc/articles/versions.md#tilde
 */
final class TildeRangeParser implements RangeParserInterface
{
    use ParseSimpleRange;

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

        return $this->parseSimpleVersionRange(
            $this->versionParser,
            $this->greaterOrEqualTo,
            $this->lessThan,
            array_slice($tokenList, 1) // Remove prefix tilde
        );
    }
}
