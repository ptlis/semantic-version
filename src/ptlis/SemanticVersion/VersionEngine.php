<?php

/**
 * Core parsing engine.
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

use ptlis\SemanticVersion\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\Label\LabelFactory;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersionFactory;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\Version\VersionFactory;
use ptlis\SemanticVersion\VersionRange\VersionRangeFactory;
use ptlis\SemanticVersion\VersionRange\VersionRange;
use ptlis\SemanticVersion\Exception\InvalidComparatorVersionException;
use ptlis\SemanticVersion\Exception\InvalidVersionException;
use ptlis\SemanticVersion\Exception\InvalidVersionRangeException;

/**
 * Core parsing engine.
 */
class VersionEngine
{
    /**
     * Parse a version number into an array of version number parts.
     *
     * @throws InvalidVersionException
     *
     * @param string $versionNo
     *
     * @return Version
     */
    public static function parseVersion($versionNo)
    {
        // TODO: Inject!
        $labelFactory = new LabelFactory();
        $versionFactory = new VersionFactory(new VersionRegex(), $labelFactory);

        return $versionFactory->parse($versionNo);
    }


    /**
     * Parse a comparator version number into an array of version number parts.
     *
     * @throws InvalidComparatorVersionException
     *
     * @param string $versionNo
     *
     * @return ComparatorVersion
     */
    public static function parseComparatorVersion($versionNo)
    {
        $labelFactory = new LabelFactory();
        $versionFactory = new VersionFactory(new VersionRegex(), $labelFactory);
        $comparatorVersionFac = new ComparatorVersionFactory(new VersionRegex(), $versionFactory);

        return $comparatorVersionFac->parse($versionNo);
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
        $labelFactory = new LabelFactory();
        $versionFactory = new VersionFactory(new VersionRegex(), $labelFactory);
        $comparatorVersionFac = new ComparatorVersionFactory(new VersionRegex(), $versionFactory);
        $versionRangeFactory = new VersionRangeFactory(new VersionRegex(), $comparatorVersionFac);

        return $versionRangeFactory->parse($versionNo);
    }
}
