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
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;

/**
 * Trait implementing method to parse a simple range (branch, tilde and wildcard) after a normalisation step.
 */
trait ParseSimpleRange
{
    /**
     * Parse a simple version range
     *
     * @param VersionParser $versionParser
     * @param ComparatorInterface $greaterOrEqualTo
     * @param ComparatorInterface $lessThan
     * @param Token[] $lowerVersionTokenList
     *
     * @return LogicalAnd
     */
    protected function parseSimpleVersionRange(
        VersionParser $versionParser,
        ComparatorInterface $greaterOrEqualTo,
        ComparatorInterface $lessThan,
        array $lowerVersionTokenList
    ) {
        $lowerVersion = $versionParser->parse($lowerVersionTokenList);

        // Upto minor version
        if (count($lowerVersionTokenList) > 3) {
            $upperVersion = new Version($lowerVersion->getMajor(), $lowerVersion->getMinor() + 1);

        // Upto patch version
        } else {
            $upperVersion = new Version($lowerVersion->getMajor() + 1);
        }

        return new LogicalAnd(
            new ComparatorVersion($greaterOrEqualTo, $lowerVersion),
            new ComparatorVersion($lessThan, $upperVersion)
        );
    }
}
