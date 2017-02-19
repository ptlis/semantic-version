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

use ptlis\SemanticVersion\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Comparator versions store a comparator & version specifying part of a version range.
 */
final class ComparatorVersionParser implements RangeParserInterface
{
    /** @var ComparatorFactory */
    private $comparatorFactory;

    /** @var VersionParser */
    private $versionParser;

    /**
     * Constructor.
     *
     * @param ComparatorFactory $comparatorFactory
     * @param VersionParser $versionParser
     */
    public function __construct(
        ComparatorFactory $comparatorFactory,
        VersionParser $versionParser
    ) {
        $this->comparatorFactory = $comparatorFactory;
        $this->versionParser = $versionParser;
    }

    /**
     * Returns true if the tokens can be parsed as a ComparatorVersion.
     *
     * @param Token[] $tokenList
     *
     * @return boolean
     */
    public function canParse(array $tokenList)
    {
        return (
            $this->versionParser->canParse($tokenList)
            || (
                $this->comparatorFactory->isComparator($tokenList[0]->getValue())
                && $this->versionParser->canParse(array_slice($tokenList, 1))
            )
        );
    }

    /**
     * Build a ComparatorVersion representing the comparator & version.
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function parse(array $tokenList)
    {
        if (!$this->canParse($tokenList)) {
            throw new \RuntimeException('Invalid comparator (>1.3.0) version range');
        }

        // Default to equality comparator
        $comparator = $this->comparatorFactory->get('=');

        // Prefixed comparator, create comparator instance and remove from token list
        if ($this->comparatorFactory->isComparator($tokenList[0]->getValue())) {
            $comparator = $this->comparatorFactory->get($tokenList[0]->getValue());
            $tokenList = array_slice($tokenList, 1);
        }

        return new ComparatorVersion(
            $comparator,
            $this->versionParser->parse($tokenList)
        );
    }
}
