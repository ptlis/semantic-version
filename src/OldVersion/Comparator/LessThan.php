<?php

/**
 * Version less than comparator.
 *
 * PHP Version 5.3
 *
 * @copyright   (c) 2014 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\OldVersion\Comparator;

use ptlis\SemanticVersion\OldVersion\VersionInterface;

/**
 * Version less than comparator.
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
        $lessThan = false;
        if ($this->compareVersionNumber($lVersion, $rVersion) || $this->compareFullLabel($lVersion, $rVersion)) {
            $lessThan = true;
        }

        return $lessThan;
    }


    /**
     * Returns true if left version number is less than right version number.
     *
     * @param VersionInterface $lVersion
     * @param VersionInterface $rVersion
     *
     * @return bool
     */
    private function compareVersionNumber(VersionInterface $lVersion, VersionInterface $rVersion)
    {
        $lessThan = false;
        if ($this->compareMajor($lVersion, $rVersion) || $this->compareMinor($lVersion, $rVersion)
                || $this->comparePatch($lVersion, $rVersion)) {
            $lessThan = true;
        }

        return $lessThan;
    }


    /**
     * Returns true if left label is less than right version label.
     *
     * @param VersionInterface $lVersion
     * @param VersionInterface $rVersion
     *
     * @return bool
     */
    private function compareFullLabel(VersionInterface $lVersion, VersionInterface $rVersion)
    {
        $lessThan = false;
        if ($this->compareLabel($lVersion, $rVersion) || $this->compareLabelVersion($lVersion, $rVersion)) {
            $lessThan = true;
        }

        return $lessThan;
    }


    /**
     * Returns true if left major is less than right major version.
     *
     * @param VersionInterface $lVersion
     * @param VersionInterface $rVersion
     *
     * @return bool
     */
    private function compareMajor(VersionInterface $lVersion, VersionInterface $rVersion)
    {
        return $lVersion->getMajor() < $rVersion->getMajor();
    }


    /**
     * Returns true if left & right major values match & left minor is less than right major version.
     *
     * @param VersionInterface $lVersion
     * @param VersionInterface $rVersion
     *
     * @return bool
     */
    private function compareMinor(VersionInterface $lVersion, VersionInterface $rVersion)
    {
        return $lVersion->getMajor() == $rVersion->getMajor()
            && $lVersion->getMinor() < $rVersion->getMinor();
    }


    /**
     * Returns true if left & right major & minor values match & left patch is less than right patch version.
     *
     * @param VersionInterface $lVersion
     * @param VersionInterface $rVersion
     *
     * @return bool
     */
    private function comparePatch(VersionInterface $lVersion, VersionInterface $rVersion)
    {
        return $lVersion->getMajor() == $rVersion->getMajor()
            && $lVersion->getMinor() == $rVersion->getMinor()
            && $lVersion->getPatch() < $rVersion->getPatch();
    }


    /**
     * Returns true if left & right major, minor & patch values match & left label precedence is less than right label
     * precedence.
     *
     * @param VersionInterface $lVersion
     * @param VersionInterface $rVersion
     *
     * @return bool
     */
    private function compareLabel(VersionInterface $lVersion, VersionInterface $rVersion)
    {
        return $lVersion->getMajor() == $rVersion->getMajor()
            && $lVersion->getMinor() == $rVersion->getMinor()
            && $lVersion->getPatch() == $rVersion->getPatch()
            && $lVersion->getLabel()->getPrecedence() < $rVersion->getLabel()->getPrecedence();
    }


    /**
     * Returns true if left & right major, minor, patch & label precedence values match & left label version is less
     * than right patch version.
     *
     * @param VersionInterface $lVersion
     * @param VersionInterface $rVersion
     *
     * @return bool
     */
    private function compareLabelVersion(VersionInterface $lVersion, VersionInterface $rVersion)
    {
        return $lVersion->getMajor() == $rVersion->getMajor()
            && $lVersion->getMinor() == $rVersion->getMinor()
            && $lVersion->getPatch() == $rVersion->getPatch()
            && $lVersion->getLabel()->getPrecedence() == $rVersion->getLabel()->getPrecedence()
            && $lVersion->getLabel()->getVersion() < $rVersion->getLabel()->getVersion();
    }
}
