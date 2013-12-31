<?php


namespace ptlis\SemanticVersion;

use ptlis\SemanticVersion\Entity\RangedVersion;
use ptlis\SemanticVersion\Entity\Version;
use ptlis\SemanticVersion\Entity\VersionRange;

/**
 * Class VersionsHandler
 * @package ptlis\HerculeServerBundle\Service
 */
class VersionEngine
{
    /**
     * Regex for parsing semantic version numbers.
     *
     * @var string
     */
    private static $versionRegex = "
        v*                                          # Optional 'v' prefix
        (?<major>[0-9]+)                            # Major Version
        (?:\.(?<minor>[0-9x]+)?)?                   # Minor Version
        (?:\.(?<patch>[0-9x]+)?)?                   # Patch
        (?:\-*
            (?<label_full>                          # Label & number (with seperator)
                (?<label>alpha|beta|rc)             # Label
                \.?(?<label_num>[0-9]+)?            # Label Number - for precedence
            )?
        )?
    ";

    private static $rangeRegex = "(?<comparator>[<>]?=?)";

    private static $rangeMinRegex = "(?<min_comparator>>=?)";

    private static $rangeMaxRegex = "(?<max_comparator><=?)";

    private static function getVersionRegex()
    {
        return '/^\s*' . static::$versionRegex . '\s*$/ix';
    }

    private static function getRangedVersionRegex()
    {
        return '/^\s*' . static::$rangeRegex . '\s*' . static::$versionRegex . '\s*$/ix';
    }

    private static function getVersionRange()
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
            .     static::$rangeMinRegex
            .     '\s*'
            .     str_replace(array_keys($minReplace), $minReplace, static::$versionRegex)
            . ')?'
            . '\s*'
            . '('
            .     static::$rangeMaxRegex
            .     '\s*'
            .     str_replace(array_keys($maxReplace), $maxReplace, static::$versionRegex)
            . ')?'
            . '\s*'
            . '('
            .     str_replace(array_keys($singleReplace), $singleReplace, static::$versionRegex)
            . ')?'
            . '\s*$/ix';
    }


    /**
     * Validate a semantic version number.
     *
     * @param string $versionNo
     *
     * @return boolean
     */
    public static function validVersion($versionNo)
    {
        return (bool)preg_match(static::getVersionRegex(), $versionNo);
    }


    /**
     * Parse a version number into an array of version number parts.
     *
     * @param string $versionNo
     *
     * @return Version | null
     */
    public static function parseVersion($versionNo)
    {
        $version = null;

        if (preg_match(static::getVersionRegex(), $versionNo, $matches)) {
            $version = static::matchesToVersion($matches);
        }

        return $version;
    }


    /**
     * Validate a ranged semantic version number.
     *
     * @param string $versionNo
     *
     * @return boolean
     */
    public static function validRangedVersion($versionNo)
    {
        return (bool)preg_match(static::getRangedVersionRegex(), $versionNo);
    }


    /**
     * Parse a ranged version number into an array of version number parts.
     *
     * @param string $versionNo
     *
     * @return RangedVersion | null
     */
    public static function parseRangedVersion($versionNo)
    {
        $rangedVersion = null;

        if (preg_match(static::getRangedVersionRegex(), $versionNo, $matches)) {
            $rangedVersion = static::matchesToRangedVersion($matches);
        }

        return $rangedVersion;
    }


    /**
     * Validate a version range string.
     *
     * @param string $versionNo
     *
     * @return boolean
     */
    public static function validVersionRange($versionNo)
    {
        return (bool)preg_match(static::getVersionRange(), $versionNo);
    }


    /**
     * Parse a version range string into a VersionRange entity.
     *
     * @param string $versionNo
     *
     * @return VersionRange | null
     */
    public static function parseVersionRange($versionNo)
    {
        $versionRange = null;

        if (preg_match(static::getVersionRange(), $versionNo, $matches)) {
            $versionRange = new VersionRange();

            if (array_key_exists('min_major', $matches) && strlen($matches['min_major'])) {
                $versionRange
                    ->setLower(static::matchesToRangedVersion($matches, 'min_'));
            }

            if (array_key_exists('max_major', $matches) && strlen($matches['max_major'])) {
                $versionRange
                    ->setUpper(static::matchesToRangedVersion($matches, 'max_'));
            }

            if (array_key_exists('single_major', $matches) && strlen($matches['single_major'])) {
                $versionRange = static::singleMatchesToVersionRange($matches);
            }

        }

        return $versionRange;
    }


    /**
     * Convert single version matches to a VersionRange entity.
     *
     * @param array $matches
     *
     * @return VersionRange
     */
    private function singleMatchesToVersionRange(array $matches)
    {
        $versionRange = new VersionRange();

        // Label & patch - exact match
        if (array_key_exists('single_patch', $matches) && strlen($matches['single_patch'])) {
            $lower = static::matchesToRangedVersion($matches, 'single_');
            $lower->setComparator(RangedVersion::EQUAL);

            $upper = static::matchesToRangedVersion($matches, 'single_');
            $upper->setComparator(RangedVersion::EQUAL);

            // Minor - range (minor inc by 1)
        } elseif (array_key_exists('single_minor', $matches) && strlen($matches['single_minor'])) {
            $lower = static::matchesToRangedVersion($matches, 'single_');
            $lower->setComparator(RangedVersion::GREATER_OR_EQUAL_TO);

            $upper = static::matchesToRangedVersion($matches, 'single_');
            $upper->getVersion()->setMinor($upper->getVersion()->getMinor() + 1);
            $upper->getVersion()->setPatch(0);
            $upper->setComparator(RangedVersion::LESS_THAN);

            // Major - range (major inc by 1;
        } else {
            $lower = static::matchesToRangedVersion($matches, 'single_');
            $lower->setComparator(RangedVersion::GREATER_OR_EQUAL_TO);

            $upper = static::matchesToRangedVersion($matches, 'single_');
            $upper->getVersion()->setMajor($upper->getVersion()->getMajor() + 1);
            $upper->getVersion()->setMinor(0);
            $upper->getVersion()->setPatch(0);
            $upper->setComparator(RangedVersion::LESS_THAN);
        }

        $versionRange
            ->setLower($lower)
            ->setUpper($upper);

        return $versionRange;
    }


    /**
     * Find out if version no is within range.
     *
     * @param Version $needle
     * @param Version $min
     * @param Version $max
     *
     * @return boolean
     */
/*    public static function inRange(Version $needle, Version $min, Version $max)
    {
        $inRange = false;
        // TODO: implement, elsewhere

        return $inRange;
    }*/


    /**
     * Transform an array of matches from preg_match to a Version entity.
     *
     * @param array         $matches
     * @param string|null   $prefix
     *
     * @return Version
     */
    private static function matchesToVersion(array $matches, $prefix = null)
    {
        $version = new Version();

        if (array_key_exists($prefix . 'major', $matches)) {
            $version->setMajor($matches[$prefix . 'major']);
        }

        if (array_key_exists($prefix . 'minor', $matches) && strlen($matches[$prefix . 'minor'])) {
            $version->setMinor($matches[$prefix . 'minor']);
        }

        if (array_key_exists($prefix . 'patch', $matches) && strlen($matches[$prefix . 'patch'])) {
            $version->setPatch($matches[$prefix . 'patch']);
        }

        if (array_key_exists($prefix . 'label', $matches) && strlen($matches[$prefix . 'label'])) {
            $version
                ->setLabel($matches[$prefix . 'label_full'])
                ->setLabelPrecedence(static::getPrecedence($matches[$prefix . 'label']));
        }

        if (array_key_exists($prefix . 'label_num', $matches) && strlen($matches[$prefix . 'label_num'])) {
            $version->setLabelNumber($matches[$prefix . 'label_num']);
        }

        return $version;
    }


    /**
     * Transform an array of matches from preg_match to a RangedVersion entity.
     *
     * @param array         $matches
     * @param string|null   $prefix
     *
     * @return RangedVersion
     */
    private static function matchesToRangedVersion(array $matches, $prefix = null)
    {
        $rangedVersion = new RangedVersion();
        $version = static::matchesToVersion($matches, $prefix);

        $rangedVersion
            ->setVersion($version);

        if (array_key_exists($prefix . 'comparator', $matches) && strlen($matches[$prefix . 'comparator'])) {
            $rangedVersion
                ->setComparator($matches[$prefix . 'comparator']);
        }

        return $rangedVersion;
    }


    /**
     * Get the precedence value for a label.
     *
     * @param $label
     *
     * @return int
     */
    private static function getPrecedence($label)
    {
        $precedence = Version::LABEL_NONE;

        switch ($label) {
            case 'alpha':
                $precedence = Version::LABEL_ALPHA;
                break;
            case 'beta':
                $precedence = Version::LABEL_BETA;
                break;
            case 'rc':
                $precedence = Version::LABEL_RC;
                break;
        }

        return $precedence;
    }
}
