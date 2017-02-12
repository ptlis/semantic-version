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
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Parser for wildcard ranges.
 *
 * Behaviour of wildcard ranges is described @ https://getcomposer.org/doc/articles/versions.md#wildcard
 */
final class WildcardRangeParser implements RangeParserInterface
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
     * Returns true if the tokens represent a wildcard range.
     *
     * @param Token[] $tokenList
     *
     * @return boolean
     */
    public function canParse(array $tokenList)
    {
        return (
            count($tokenList) > 0
            && Token::WILDCARD_DIGITS === $tokenList[count($tokenList) - 1]->getType()
            && $this->versionParser->canParse(array_slice($tokenList, 0, count($tokenList) - 1))
        );
    }

    /**
     * Build a comparator representing the wildcard range.
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
            array_slice($tokenList, 0, count($tokenList) - 1) // Remove trailing '*' when parsing as version
        );
    }
}
