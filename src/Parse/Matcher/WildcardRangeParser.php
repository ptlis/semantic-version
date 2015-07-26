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

use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessThan;
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
            $range = new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version($tokenList[0]->getValue(), $tokenList[2]->getValue())
                ),
                new ComparatorVersion(
                    new LessThan(),
                    new Version($tokenList[0]->getValue() + 1)
                )
            );

            // Upto patch version
        } else {
            $range = new LogicalAnd(
                new ComparatorVersion(
                    new GreaterOrEqualTo(),
                    new Version($tokenList[0]->getValue(), $tokenList[2]->getValue(), $tokenList[4]->getValue())
                ),
                new ComparatorVersion(
                    new LessThan(),
                    new Version($tokenList[0]->getValue(), $tokenList[2]->getValue() + 1)
                )
            );
        }

        return $range;
    }
}
