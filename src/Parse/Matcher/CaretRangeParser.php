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
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\Version\Version;

/**
 * Parser for caret ranges.
 *
 * Behaviour of caret ranges is described @ https://getcomposer.org/doc/articles/versions.md#caret
 */
class CaretRangeParser implements RangeParserInterface
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
     * Constructor.
     *
     * @param ComparatorInterface $greaterOrEqualTo
     * @param ComparatorInterface $lessThan
     */
    public function __construct(ComparatorInterface $greaterOrEqualTo, ComparatorInterface $lessThan)
    {
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
        return count($tokenList) > 0
            && Token::CARET_RANGE === $tokenList[0]->getType();
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
        $minor = 0;
        $patch = 0;

        if (count($tokenList) > 4) {
            $minor = $tokenList[3]->getValue();
        }

        if (6 === count($tokenList)) {
            $patch = $tokenList[5]->getValue();
        }

        return new LogicalAnd(
            new ComparatorVersion(
                $this->greaterOrEqualTo,
                new Version($tokenList[1]->getValue(), $minor, $patch)
            ),
            new ComparatorVersion(
                $this->lessThan,
                new Version($tokenList[1]->getValue() + 1)
            )
        );
    }
}
