<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\VersionRange;

use ptlis\SemanticVersion\Comparator\ComparatorInterface;
use ptlis\SemanticVersion\Version\VersionInterface;

/**
 * Simple version number & comparator.
 */
final class ComparatorVersion implements VersionRangeInterface
{
    /** @var ComparatorInterface */
    private $comparator;

    /** @var VersionInterface */
    private $version;


    /**
     * Constructor.
     *
     * @param ComparatorInterface $comparator
     * @param VersionInterface $version
     */
    public function __construct(ComparatorInterface $comparator, VersionInterface $version)
    {
        $this->comparator = $comparator;
        $this->version = $version;
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy(VersionInterface $version)
    {
        return $this->comparator->compare($version, $this->version);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return $this->comparator . $this->version;
    }
}
