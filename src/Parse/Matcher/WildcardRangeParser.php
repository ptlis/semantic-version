<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Parse\Matcher;

use ptlis\SemanticVersion\Comparator\ComparatorInterface;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Parser for wildcard ranges.
 *
 * Behaviour of wildcard ranges is described @ https://getcomposer.org/doc/articles/versions.md#wildcard
 */
class WildcardRangeParser implements RangeParserInterface
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
    public function __construct(ComparatorInterface $greaterOrEqualTo, ComparatorInterface $lessThan) {
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
        return count($tokenList) > 0
            && Token::WILDCARD_DIGITS === $tokenList[count($tokenList) - 1]->getType();
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
        // Upto minor version
        if (3 === count($tokenList)) {
            $lowerVersion = new Version($tokenList[0]->getValue(), $tokenList[2]->getValue());
            $upperVersion = new Version($tokenList[0]->getValue() + 1);

        // Upto patch version
        } else {
            $lowerVersion = new Version($tokenList[0]->getValue(), $tokenList[2]->getValue(), $tokenList[4]->getValue());
            $upperVersion = new Version($tokenList[0]->getValue(), $tokenList[2]->getValue() + 1);
        }

        $range = new LogicalAnd(
            new ComparatorVersion(
                $this->greaterOrEqualTo,
                $lowerVersion
            ),
            new ComparatorVersion(
                $this->lessThan,
                $upperVersion
            )
        );
        return $range;
    }
}
