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

use ptlis\SemanticVersion\Version\VersionInterface;

/**
 * Version range with a left & right value - either may true to fulfill the isSatisfiedBy requirement.
 */
final class LogicalOr implements VersionRangeInterface
{
    /** @var VersionRangeInterface */
    private $leftRange;

    /** @var VersionRangeInterface */
    private $rightRange;


    /**
     * Constructor.
     *
     * @param VersionRangeInterface $leftRange
     * @param VersionRangeInterface $rightRange
     */
    public function __construct(VersionRangeInterface $leftRange, VersionRangeInterface $rightRange)
    {
        $this->leftRange = $leftRange;
        $this->rightRange = $rightRange;
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy(VersionInterface $version)
    {
        return $this->leftRange->isSatisfiedBy($version) || $this->rightRange->isSatisfiedBy($version);
    }


    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return $this->leftRange . '|' . $this->rightRange;
    }
}
