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

use ptlis\SemanticVersion\BoundingPair\BoundingPairRegexProviderInterface;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersionRegexProviderInterface;
use ptlis\SemanticVersion\Version\VersionRegexProviderInterface;

/**
 * Utility class to provide regular expressions used when parsing version numbers, ranges etc.
 */
class VersionRegex implements VersionRegexProviderInterface, BoundingPairRegexProviderInterface,
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
        ((?:\.(?<minor>[0-9x\*]+))|(\.?))?          # Minor Version or trailing dot
        ((?:\.(?<patch>[0-9x\*]+))|(\.?))?          # Patch or trailing dot
        (?:\-?                                      # Label & number (with separator)
            (?<label_full>
                (?<label>[a-z]+)                    # Label
                \.?(?<label_num>[0-9]+)?            # Label Number - for precedence
                (\+(?<label_meta>[0-9a-z\-\.]+))?   # Build metadata
            )
        )?
    ";


    /**
     * Base regex for parsing comparators.
     *
     * @var string
     */
    private $comparatorRegex = "(?<comparator>[<>]?=?)          # Comparator\n";


    /**
     * Regex for parsing minimum comparators.
     *
     * @var string
     */
    private $comparatorMinRegex = "(?<comparator>>=?)           # Min Comparator\n";


    /**
     * Regex for parsing maximum comparators.
     *
     * @var string
     */
    private $comparatorMaxRegex = "(?<comparator><=?)           # Max Comparator\n";


    /**
     * Returns the regex to parse a version number.
     *
     * @return string
     */
    public function getVersion()
    {
        return '/^\s*' . $this->versionRegex . '\s*\-*$/ix';
    }


    /**
     * Returns the regex to parse a version number with comparator.
     *
     * @return string
     */
    public function getComparatorVersion()
    {
        return '/^\s*' . $this->comparatorRegex . '\s*' . $this->versionRegex . '\s*\-*$/ix';
    }


    /**
     * Returns the regex to parse a bounding pair.
     *
     * @return string
     */
    public function getBoundingPair()
    {
        $versionKeyList = array(
            'major',
            'minor',
            'patch',
            'label_full',
            'label',
            'label_num',
            'label_meta'
        );

        $minComp            = $this->getPrefixedRegex($this->comparatorMinRegex, 'min_', array('comparator'));
        $minCompVersion     = $this->getPrefixedRegex($this->versionRegex, 'min_', $versionKeyList);
        $maxComp            = $this->getPrefixedRegex($this->comparatorMaxRegex, 'max_', array('comparator'));
        $maxCompVersion     = $this->getPrefixedRegex($this->versionRegex, 'max_', $versionKeyList);

        $minHyphenVersion   = $this->getPrefixedRegex($this->versionRegex, 'min_hyphen_', $versionKeyList);
        $maxHyphenVersion   = $this->getPrefixedRegex($this->versionRegex, 'max_hyphen_', $versionKeyList);

        $tilde              = $this->getPrefixedRegex($this->versionRegex, 'tilde_', $versionKeyList);

        $single              = $this->getPrefixedRegex($this->versionRegex, 'single_', $versionKeyList);

        return "
            /^
                (
                    (
                        (
                            \s*
                            $minComp
                            \s*
                            $minCompVersion
                            \s*
                            \-*
                        )|(
                            \s*
                            $maxComp
                            \s*
                            $maxCompVersion
                            \s*
                            \-*
                        )
                    ){1,2}
                )?

                (
                    (
                        \s*
                        $minHyphenVersion
                        \s*
                    )
                    \-
                    (
                        \s*
                        $maxHyphenVersion
                        \s*
                    )
                )?

                (
                    \s*
                    (?<tilde>~)
                    \s*
                    $tilde
                    \s*
                )?

                (
                    \s*
                    $single
                    \s*
                )?
            $/ix";
    }


    /**
     * Prefixes a the named subpatterns in a regex.
     *
     * @param string   $regex
     * @param string   $prefix
     * @param string[] $keyList
     *
     * @return string
     */
    private function getPrefixedRegex($regex, $prefix, array $keyList)
    {
        $search = array();
        $replace = array();
        foreach ($keyList as $key) {
            $search[] = '<' . $key . '>';
            $replace[] = '<' . $prefix . $key . '>';
        }

        return str_replace($search, $replace, $regex);
    }
}
