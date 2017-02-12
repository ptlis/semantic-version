<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion;

use ptlis\SemanticVersion\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Parse\RangeMatcher\BranchParser;
use ptlis\SemanticVersion\Parse\RangeMatcher\CaretRangeParser;
use ptlis\SemanticVersion\Parse\RangeMatcher\ComparatorVersionParser;
use ptlis\SemanticVersion\Parse\RangeMatcher\HyphenatedRangeParser;
use ptlis\SemanticVersion\Parse\RangeMatcher\TildeRangeParser;
use ptlis\SemanticVersion\Parse\RangeMatcher\WildcardRangeParser;
use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\Parse\VersionRangeParser;
use ptlis\SemanticVersion\Parse\VersionTokenizer;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\VersionInterface;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Simple class to provide version parsing with good defaults.
 */
final class VersionEngine
{
    /** @var VersionTokenizer */
    private $tokenizer;

    /** @var VersionRangeParser */
    private $versionRangeParser;

    /** @var VersionParser */
    private $versionParser;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $labelBuilder = new LabelBuilder();
        $this->versionParser = new VersionParser($labelBuilder);
        $comparatorFactory = new ComparatorFactory();

        $wildcardParser = new WildcardRangeParser(
            $this->versionParser,
            $comparatorFactory->get('>='),
            $comparatorFactory->get('<')
        );

        $matcherList = [
            new CaretRangeParser(
                $this->versionParser,
                $comparatorFactory->get('>='),
                $comparatorFactory->get('<')
            ),
            new TildeRangeParser(
                $this->versionParser,
                $comparatorFactory->get('>='),
                $comparatorFactory->get('<')
            ),
            $wildcardParser,
            new BranchParser(
                $this->versionParser,
                $comparatorFactory->get('>='),
                $comparatorFactory->get('<')
            ),
            new ComparatorVersionParser(
                $comparatorFactory,
                $this->versionParser
            ),
            new HyphenatedRangeParser(
                $this->versionParser,
                $comparatorFactory->get('>='),
                $comparatorFactory->get('<'),
                $comparatorFactory->get('<=')
            )
        ];

        $this->tokenizer = new VersionTokenizer();
        $this->versionRangeParser = new VersionRangeParser($matcherList);
    }

    /**
     * Parse a semantic version string into an object implementing VersionInterface.
     *
     * @param string $versionString
     *
     * @throws \InvalidArgumentException When version string is invalid.
     *
     * @return VersionInterface
     */
    public function parseVersion($versionString)
    {
        $tokenList = $this->tokenizer->tokenize($versionString);

        try {
            $version = $this->versionParser->parse($tokenList);
        } catch (\RuntimeException $e) {
            throw new \InvalidArgumentException('"' . $versionString . '" is not a valid semantic version number', $e->getCode(), $e);
        }

        return $version;
    }

    /**
     * Parse a version range & return an object implementing VersionRangeInterface that encodes those rules.
     *
     * @param string $rangeString
     *
     * @throws \InvalidArgumentException When version range string is invalid.
     *
     * @return VersionRangeInterface
     */
    public function parseRange($rangeString)
    {
        $tokenList = $this->tokenizer->tokenize($rangeString);

        try {
            $range = $this->versionRangeParser->parseRange($tokenList);
        } catch (\RuntimeException $e) {
            throw new \InvalidArgumentException('"' . $rangeString . '" is not a valid version range', $e->getCode(), $e);
        }

        return $range;
    }
}
