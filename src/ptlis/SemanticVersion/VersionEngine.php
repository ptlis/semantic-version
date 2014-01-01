<?php


namespace ptlis\SemanticVersion;

use ptlis\SemanticVersion\Entity\RangedVersion;
use ptlis\SemanticVersion\Entity\Version;
use ptlis\SemanticVersion\Entity\VersionRange;
use ptlis\SemanticVersion\Exception\InvalidRangedVersionException;
use ptlis\SemanticVersion\Exception\InvalidVersionException;
use ptlis\SemanticVersion\Exception\InvalidVersionRangeException;

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
        (?<major>[0-9]+|x|\*)                       # Major Version
        (?:\.(?<minor>[0-9]+|x|\*)?)?               # Minor Version
        (?:\.(?<patch>[0-9]+|x|\*)?)?               # Patch
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
            .     '(?<single_tilde>~)?'
            .     str_replace(array_keys($singleReplace), $singleReplace, static::$versionRegex)
            . ')?'
            . '\s*$/ix';
    }


    /**
     * Parse a version number into an array of version number parts.
     *
     * @throws Exception\InvalidVersionException
     *
     * @param string $versionNo
     *
     * @return Version
     */
    public static function parseVersion($versionNo)
    {
        if (preg_match(static::getVersionRegex(), $versionNo, $matches)) {
            $version = static::matchesToVersion($matches);
        } else {
            throw new InvalidVersionException('The version number "' . $versionNo . '" could not be parsed.');
        }

        return $version;
    }


    /**
     * Parse a ranged version number into an array of version number parts.
     *
     * @throws InvalidRangedVersionException
     *
     * @param string $versionNo
     *
     * @return RangedVersion
     */
    public static function parseRangedVersion($versionNo)
    {
        if (preg_match(static::getRangedVersionRegex(), $versionNo, $matches)) {
            $rangedVersion = static::matchesToRangedVersion($matches);
        } else {
            throw new InvalidRangedVersionException('The ranged version "' . $versionNo . '" could not be parsed.');
        }

        return $rangedVersion;
    }


    /**
     * Parse a version range string into a VersionRange entity.
     *
     * @throws InvalidVersionRangeException
     *
     * @param string $versionNo
     *
     * @return VersionRange
     */
    public static function parseVersionRange($versionNo)
    {
        if (preg_match(static::getVersionRange(), $versionNo, $matches)) {
            $versionRange = new VersionRange();

            try {
                if (array_key_exists('min_major', $matches) && strlen($matches['min_major'])) {
                    $versionRange->setLower(static::matchesToRangedVersion($matches, 'min_'));
                }

                if (array_key_exists('max_major', $matches) && strlen($matches['max_major'])) {
                    $versionRange->setUpper(static::matchesToRangedVersion($matches, 'max_'));
                }

                if (array_key_exists('single_major', $matches) && strlen($matches['single_major'])) {
                    $versionRange = static::singleMatchesToVersionRange($matches);
                }

            } catch (InvalidRangedVersionException $e) {
                throw new InvalidVersionRangeException(
                    'The version range "' . $versionNo . '" could not be parsed.',
                    $e
                );

            }

            if (is_null($versionRange->getLower()) && is_null($versionRange->getUpper())) {
                throw new InvalidVersionRangeException('The version range "' . $versionNo . '" could not be parsed.');
            }

        } else {
            throw new InvalidVersionRangeException('The version range "' . $versionNo . '" could not be parsed.');
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

        // Tilde match
        if (array_key_exists('single_tilde', $matches) && strlen($matches['single_tilde'])) {

            // Full version tilde match
            if (array_key_exists('single_patch', $matches) && is_numeric($matches['single_patch'])) {
                $lower = static::matchesToRangedVersion($matches, 'single_');
                $lower->setComparator(RangedVersion::GREATER_OR_EQUAL_TO);

                $upper = static::matchesToRangedVersion($matches, 'single_');
                $upper->getVersion()->setMinor($upper->getVersion()->getMinor() + 1);
                $upper->getVersion()->setPatch(0);
                $upper->setComparator(RangedVersion::LESS_THAN);

            // Major & Minor tilde match
            } elseif (array_key_exists('single_minor', $matches) && is_numeric($matches['single_minor'])) {
                $lower = static::matchesToRangedVersion($matches, 'single_');
                $lower->getVersion()->setPatch(0);
                $lower->setComparator(RangedVersion::GREATER_OR_EQUAL_TO);

                $upper = static::matchesToRangedVersion($matches, 'single_');
                $upper->getVersion()->setMajor($upper->getVersion()->getMajor() + 1);
                $upper->getVersion()->setMinor(0);
                $upper->getVersion()->setPatch(0);
                $upper->setComparator(RangedVersion::LESS_THAN);

            // Major tilde match
            } else {
                $lower = static::matchesToRangedVersion($matches, 'single_');
                $lower->getVersion()->setMinor(0);
                $lower->getVersion()->setPatch(0);
                $lower->setComparator(RangedVersion::GREATER_OR_EQUAL_TO);

                $upper = static::matchesToRangedVersion($matches, 'single_');
                $upper->getVersion()->setMajor($upper->getVersion()->getMajor() + 1);
                $upper->getVersion()->setMinor(0);
                $upper->getVersion()->setPatch(0);
                $upper->setComparator(RangedVersion::LESS_THAN);
            }

        // Label & patch - exact match
        } elseif (array_key_exists('single_patch', $matches) && strlen($matches['single_patch'])) {
            $lower = static::matchesToRangedVersion($matches, 'single_');
            $lower->setComparator(RangedVersion::EQUAL_TO);

            $upper = static::matchesToRangedVersion($matches, 'single_');
            $upper->setComparator(RangedVersion::EQUAL_TO);

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

        if (!is_null($lower->getComparator())) {
            $versionRange->setLower($lower);
        }

        if (!is_null($upper->getComparator())) {
            $versionRange->setUpper($upper);
        }

        return $versionRange;
    }


    /**
     * Transform an array of matches from preg_match to a Version entity.
     *
     * @throws InvalidVersionException
     *
     * @param array         $matches
     * @param string|null   $prefix
     *
     * @return Version
     */
    private static function matchesToVersion(array $matches, $prefix = null)
    {
        $majorPresent = array_key_exists($prefix . 'major', $matches);
        $minorPresent = array_key_exists($prefix . 'minor', $matches) && strlen($matches[$prefix . 'minor']);
        $patchPresent = array_key_exists($prefix . 'patch', $matches) && strlen($matches[$prefix . 'patch']);
        $labelPresent = array_key_exists($prefix . 'label', $matches) && strlen($matches[$prefix . 'label']);
        $labelNumPresent = array_key_exists($prefix . 'label_num', $matches) && strlen($matches[$prefix . 'label_num']);

        // Handle error case where major version is omitted or a wildcard but minor/patch is a number
        if ((!$majorPresent || $matches[$prefix . 'major'] === '*' || $matches[$prefix . 'major'] === 'x')
                && (($minorPresent && $matches[$prefix . 'minor'] !== '*' && $matches[$prefix . 'minor'] !== 'x')
                || ($patchPresent && $matches[$prefix . 'patch'] !== '*' && $matches[$prefix . 'patch'] !== 'x'))
        ) {
            throw new InvalidVersionException('The version number "' . $matches[0] . '" could not be parsed.');
        }

        // Handle error case where minor version is omitted or a wildcard but patch is a number
        if ((!$minorPresent || $matches[$prefix . 'minor'] === '*' || $matches[$prefix . 'minor'] === 'x')
            && ($patchPresent && $matches[$prefix . 'patch'] !== '*' && $matches[$prefix . 'patch'] !== 'x')
        ) {
            throw new InvalidVersionException('The version number "' . $matches[0] . '" could not be parsed.');
        }

        $version = new Version();

        if ($majorPresent) {
            $version->setMajor($matches[$prefix . 'major']);
        }

        if ($minorPresent) {
            $version->setMinor($matches[$prefix . 'minor']);
        }

        if ($patchPresent) {
            $version->setPatch($matches[$prefix . 'patch']);
        }

        if ($labelPresent) {
            $version
                ->setLabel($matches[$prefix . 'label_full'])
                ->setLabelPrecedence(static::getPrecedence($matches[$prefix . 'label']));
        }

        if ($labelNumPresent) {
            $version->setLabelNumber($matches[$prefix . 'label_num']);
        }

        return $version;
    }


    /**
     * Transform an array of matches from preg_match to a RangedVersion entity.
     *
     * @throws InvalidRangedVersionException
     *
     * @param array         $matches
     * @param string|null   $prefix
     *
     * @return RangedVersion
     */
    private static function matchesToRangedVersion(array $matches, $prefix = null)
    {
        $rangedVersion = new RangedVersion();
        try {
            $version = static::matchesToVersion($matches, $prefix);
        } catch (InvalidVersionException $e) {
            throw new InvalidRangedVersionException(
                'The ranged version "' . $matches[0] . '" could not be parsed.',
                $e
            );
        }

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
