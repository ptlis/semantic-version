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

use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\Comparator\LessThan;

/**
 * Parser for caret ranges.
 *
 * Behaviour of caret ranges is described @ https://getcomposer.org/doc/articles/versions.md#caret
 */
class CaretRangeParser implements RangeParserInterface
{
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
                new GreaterOrEqualTo(),
                new Version($tokenList[1]->getValue(), $minor, $patch)
            ),
            new ComparatorVersion(
                new LessThan(),
                new Version($tokenList[1]->getValue() + 1)
            )
        );
    }
}
