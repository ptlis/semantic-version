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
 * Version less than or equal comparator.
 */
final class LessOrEqualTo implements ComparatorInterface
{
    /**
     * Return true if the left version is less or equal to the right version.
     *
     * @param VersionInterface $lVersion
     * @param VersionInterface $rVersion
     *
     * @return boolean
     */
    public function compare(VersionInterface $lVersion, VersionInterface $rVersion)
    {
        return (
            (new LessThan())->compare($lVersion, $rVersion)
            || (new EqualTo())->compare($lVersion, $rVersion)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return '<=';
    }
}
