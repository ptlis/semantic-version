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
use ptlis\SemanticVersion\Parse\Matcher\BranchParser;
use ptlis\SemanticVersion\Parse\Matcher\CaretRangeParser;
use ptlis\SemanticVersion\Parse\Matcher\ComparatorVersionParser;
use ptlis\SemanticVersion\Parse\Matcher\HyphenatedRangeParser;
use ptlis\SemanticVersion\Parse\Matcher\TildeRangeParser;
use ptlis\SemanticVersion\Parse\Matcher\WildcardRangeParser;
use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\Parse\VersionTokenizer;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\VersionBuilder;
use ptlis\SemanticVersion\Version\VersionInterface;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Simple class to provide version parsing with good defaults.
 */
final class VersionEngine
{
    /**
     * @var VersionTokenizer
     */
    private $tokenizer;

    /**
     * @var VersionParser
     */
    private $parser;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $comparatorFactory = new ComparatorFactory();
        $versionBuilder = new VersionBuilder(new LabelBuilder());

        $wildcardParser = new WildcardRangeParser(
            $comparatorFactory->get('>='),
            $comparatorFactory->get('<')
        );

        $matcherList = [
            new CaretRangeParser(
                $comparatorFactory->get('>='),
                $comparatorFactory->get('<')
            ),
            new TildeRangeParser($wildcardParser),
            $wildcardParser,
            new BranchParser($wildcardParser),
            new ComparatorVersionParser(
                $comparatorFactory,
                $versionBuilder
            ),
            new HyphenatedRangeParser(
                $versionBuilder,
                $comparatorFactory->get('>='),
                $comparatorFactory->get('<'),
                $comparatorFactory->get('<=')
            )
        ];

        $this->tokenizer = new VersionTokenizer();
        $this->parser = new VersionParser($matcherList);
    }

    /**
     * Parse a semantic version string into an object implementing VersionInterface.
     *
     * @todo Hacky - create a better method for handling this.
     *
     * @param string $versionString
     *
     * @return VersionInterface
     */
    public function parseVersion($versionString)
    {
        $tokenList = $this->tokenizer->tokenize($versionString);

        $range = $this->parser->parseRange($tokenList);

        if (!($range instanceof ComparatorVersion)) {
            throw new \InvalidArgumentException(
                '"' . $versionString . '" is not a valid semantic version number'
            );
        }

        return $range->getVersion();
    }

    /**
     * Parse a version range & return an object implementing VersionRangeInterface that encodes those rules.
     *
     * @param string $rangeString
     *
     * @return VersionRangeInterface
     */
    public function parseRange($rangeString)
    {
        $tokenList = $this->tokenizer->tokenize($rangeString);

        return $this->parser->parseRange($tokenList);
    }
}
