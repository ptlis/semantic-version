<?php

/**
 * Less than comparator.
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

namespace ptlis\SemanticVersion\Comparator;

use ptlis\SemanticVersion\Version\VersionInterface;

/**
 * Less than comparator.
 */
class LessThan extends AbstractComparator
{
    /**
     * Retrieve the comparator's symbol.
     *
     * @return string
     */
    public static function getSymbol()
    {
        return '<';
    }


    /**
     * Return true if the left version is less than right version.
     *
     * @param VersionInterface $lVersion
     * @param VersionInterface $rVersion
     *
     * @return boolean
     */
    public function compare(VersionInterface $lVersion, VersionInterface $rVersion)
    {
        switch (true) {
            case ($lVersion->getMajor() < $rVersion->getMajor()):
                $lessThan = true;
                break;

            case ($lVersion->getMajor() == $rVersion->getMajor()
                && $lVersion->getMinor() < $rVersion->getMinor()):
                $lessThan = true;
                break;

            case ($lVersion->getMajor() == $rVersion->getMajor()
                && $lVersion->getMinor() == $rVersion->getMinor()
                && $lVersion->getPatch() < $rVersion->getPatch()):
                $lessThan = true;
                break;

            case ($lVersion->getMajor() == $rVersion->getMajor()
                && $lVersion->getMinor() == $rVersion->getMinor()
                && $lVersion->getPatch() == $rVersion->getPatch()
                && $lVersion->getLabel()->getPrecedence() < $rVersion->getLabel()->getPrecedence()):
                $lessThan = true;
                break;

            case ($lVersion->getMajor() == $rVersion->getMajor()
                && $lVersion->getMinor() == $rVersion->getMinor()
                && $lVersion->getPatch() == $rVersion->getPatch()
                && $lVersion->getLabel()->getPrecedence() == $rVersion->getLabel()->getPrecedence()
                && $lVersion->getLabel()->getVersion() < $rVersion->getLabel()->getVersion()):
                $lessThan = true;
                break;

            default:
                $lessThan = false;
                break;
        }

        return $lessThan;
    }
}
