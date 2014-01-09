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
 * Simple class to provide version parsing with good defaults.
 */
class VersionEngine
{
    /**
     * @var VersionRegex
     */
    private $regexProvider;

    /**
     * @var LabelFactory
     */
    private $labelFac;

    /**
     * @var VersionFactory
     */
    private $versionFac;

    /**
     * @var ComparatorVersionFactory
     */
    private $comparatorVersionFac;

    /**
     * @var VersionRangeFactory
     */
    private $versionRangeFac;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->regexProvider = new VersionRegex();
        $this->labelFac = new LabelFactory();
        $this->versionFac = new VersionFactory($this->regexProvider, $this->labelFac);
        $this->comparatorVersionFac = new ComparatorVersionFactory($this->regexProvider, $this->versionFac);
        $this->versionRangeFac = new VersionRangeFactory(
            $this->regexProvider,
            $this->comparatorVersionFac,
            $this->versionFac
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
     * Parse a version range string into a VersionRange entity.
     *
     * @throws InvalidVersionRangeException
     *
     * @param string $versionNo
     *
     * @return VersionRange
     */
    public function parseVersionRange($versionNo)
    {
        return $this->versionRangeFac->parse($versionNo);
    }
}
