<?php

/**
 * Utility class to provide regular expressions used when parsing version numbers, ranges etc.
 *
 * PHP Version 5.4
 *
 * @copyright   (c) 2014 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion;

use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersionRegexProviderInterface;
use ptlis\SemanticVersion\Version\VersionRegexProviderInterface;
use ptlis\SemanticVersion\VersionRange\VersionRangeRegexProviderInterface;

/**
 * Utility class to provide regular expressions used when parsing version numbers, ranges etc.
 */
class VersionRegex implements VersionRegexProviderInterface, VersionRangeRegexProviderInterface,
ComparatorVersionRegexProviderInterface
{
    /**
     * Base regex for parsing semantic version numbers.
     *
     * @var string
     */
    private $versionRegex = "
        v*                                          # Optional 'v' prefix
        (?<major>[0-9]+|x|\*)                       # Major Version
        (?:\.(?<minor>[0-9]+|x|\*)?)?               # Minor Version
        (?:\.(?<patch>[0-9]+|x|\*)?)?               # Patch
        (?:\-*
            (?<label_full>                          # Label & number (with separator)
                (?<label>alpha|beta|rc)             # Label
                \.?(?<label_num>[0-9]+)?            # Label Number - for precedence
            )?
        )?
    ";


    /**
     * Base regex for parsing comparators.
     *
     * @var string
     */
    private $comparatorRegex = "(?<comparator>[<>]?=?)";


    /**
     * Regex for parsing minimum comparators.
     *
     * @var string
     */
    private $comparatorMinRegex = "(?<min_comparator>>=?)";


    /**
     * Regex for parsing maximum comparators.
     *
     * @var string
     */
    private $comparatorMaxRegex = "(?<max_comparator><=?)";


    /**
     * Returns the regex to parse a version number.
     *
     * @return string
     */
    public function getVersion()
    {
        return '/^\s*' . $this->versionRegex . '\s*$/ix';
    }


    /**
     * Returns the regex to parse a version number with comparator.
     *
     * @return string
     */
    public function getComparatorVersion()
    {
        return '/^\s*' . $this->comparatorRegex . '\s*' . $this->versionRegex . '\s*$/ix';
    }


    /**
     * Returns the regex to parse a version range.
     *
     * @return string
     */
    public function getVersionRange()
    {
        $minReplace = [
            '<major>'       => '<min_major>',
            '<minor>'       => '<min_minor>',
            '<patch>'       => '<min_patch>',
            '<label_full>'  => '<min_label_full>',
            '<label>'       => '<min_label>',
            '<label_num>'   => '<min_label_num>'
        ];

        $maxReplace = [
            '<major>'       => '<max_major>',
            '<minor>'       => '<max_minor>',
            '<patch>'       => '<max_patch>',
            '<label_full>'  => '<max_label_full>',
            '<label>'       => '<max_label>',
            '<label_num>'   => '<max_label_num>'
        ];

        $singleReplace = [
            '<major>'       => '<single_major>',
            '<minor>'       => '<single_minor>',
            '<patch>'       => '<single_patch>',
            '<label_full>'  => '<single_label_full>',
            '<label>'       => '<single_label>',
            '<label_num>'   => '<single_label_num>'
        ];

        return '/^\s*'
        . '('
        .     $this->comparatorMinRegex
        .     '\s*'
        .     str_replace(array_keys($minReplace), $minReplace, $this->versionRegex)
        . ')?'
        . '\s*'
        . '('
        .     $this->comparatorMaxRegex
        .     '\s*'
        .     str_replace(array_keys($maxReplace), $maxReplace, $this->versionRegex)
        . ')?'
        . '\s*'
        . '('
        .     '(?<single_tilde>~)?'
        .     str_replace(array_keys($singleReplace), $singleReplace, $this->versionRegex)
        . ')?'
        . '\s*$/ix';
    }
}
