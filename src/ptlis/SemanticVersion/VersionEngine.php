<?php

/**
 * Simple class to provide version parsing with good defaults.
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

use ptlis\SemanticVersion\BoundingPair\BoundingPair;
use ptlis\SemanticVersion\BoundingPair\BoundingPairFactory;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersionFactory;
use ptlis\SemanticVersion\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Exception\InvalidBoundingPairException;
use ptlis\SemanticVersion\Exception\InvalidComparatorVersionException;
use ptlis\SemanticVersion\Exception\InvalidVersionException;
use ptlis\SemanticVersion\Label\LabelFactory;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\Version\VersionFactory;

/**
 * Simple class to provide version parsing with good defaults.
 */
class VersionEngine
{
    /**
     * @var VersionFactory
     */
    private $versionFac;

    /**
     * @var ComparatorVersionFactory
     */
    private $comparatorVersionFac;

    /**
     * @var BoundingPairFactory
     */
    private $versionRangeFac;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $regexProvider = new VersionRegex();
        $labelFac = new LabelFactory();
        $comparatorFac = new ComparatorFactory();
        $this->versionFac = new VersionFactory($regexProvider, $labelFac);
        $this->comparatorVersionFac = new ComparatorVersionFactory($regexProvider, $this->versionFac, $comparatorFac);
        $this->versionRangeFac = new BoundingPairFactory(
            $regexProvider,
            $this->comparatorVersionFac,
            $this->versionFac,
            $comparatorFac
        );
    }


    /**
     * Parse a version number into an array of version number parts.
     *
     * @throws InvalidVersionException
     *
     * @param string $versionNo
     *
     * @return Version
     */
    public function parseVersion($versionNo)
    {
        return $this->versionFac->parse($versionNo);
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
    public function parseComparatorVersion($versionNo)
    {
        return $this->comparatorVersionFac->parse($versionNo);
    }


    /**
     * Parse a bounding pair string into a BoundingPair entity.
     *
     * @throws InvalidBoundingPairException
     *
     * @param string $versionNo
     *
     * @return BoundingPair
     */
    public function parseBoundingPair($versionNo)
    {
        return $this->versionRangeFac->parse($versionNo);
    }
}
