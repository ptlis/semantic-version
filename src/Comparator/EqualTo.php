<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Comparator;

use ptlis\SemanticVersion\Version\VersionInterface;

/**
 * Version equality comparator.
 */
final class EqualTo implements ComparatorInterface
{
    /**
     * Return true if the versions match.
     *
     * @param VersionInterface $lVersion
     * @param VersionInterface $rVersion
     *
     * @return boolean
     */
    public function compare(VersionInterface $lVersion, VersionInterface $rVersion)
    {
        return ($lVersion->__toString() === $rVersion->__toString());
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return '=';
    }
}
