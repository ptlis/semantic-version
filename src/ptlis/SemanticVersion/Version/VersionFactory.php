<?php

/**
 * Factory to create Versions.
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

namespace ptlis\SemanticVersion\Version;

use ptlis\SemanticVersion\Exception\InvalidVersionException;
use ptlis\SemanticVersion\Label\LabelFactory;

/**
 * Factory to create Versions.
 */
class VersionFactory
{
    /**
     * @var VersionRegexProviderInterface
     */
    private $regexProvider;

    /**
     * @var LabelFactory
     */
    private $labelFactory;

    /**
     * Constructor
     *
     * @param VersionRegexProviderInterface $regexProvider
     * @param LabelFactory                  $labelFactory
     */
    public function __construct(VersionRegexProviderInterface $regexProvider, LabelFactory $labelFactory)
    {
        $this->regexProvider = $regexProvider;
        $this->labelFactory = $labelFactory;
    }


    /**
     * Parse a string version number & return a Version object.
     *
     * @throws InvalidVersionException
     *
     * @param $versionNo
     *
     * @return Version
     */
    public function parse($versionNo)
    {
        if (preg_match($this->regexProvider->getVersion(), $versionNo, $versionArr)) {
            $version = $this->getFromArray($versionArr);
        } else {
            throw new InvalidVersionException('The version number "' . $versionNo . '" could not be parsed.');
        }

        return $version;
    }


    /**
     * Create a Version object from the provided array of data
     *
     * @throws InvalidVersionException
     *
     * @param array         $versionArr
     * @param string|null   $prefix
     *
     * @return Version
     */
    public function getFromArray(array $versionArr, $prefix = null)
    {
        $minorPresent = array_key_exists($prefix . 'minor', $versionArr) && strlen($versionArr[$prefix . 'minor']);
        $patchPresent = array_key_exists($prefix . 'patch', $versionArr) && strlen($versionArr[$prefix . 'patch']);
        $labelPresent = array_key_exists($prefix . 'label', $versionArr) && strlen($versionArr[$prefix . 'label']);
        $labelNumPresent = array_key_exists($prefix . 'label_num', $versionArr)
            && strlen($versionArr[$prefix . 'label_num']);

        $this->validateVersionArray($versionArr, $prefix);

        $version = new Version();

        $version->setMajor($versionArr[$prefix . 'major']);

        if ($minorPresent) {
            $version->setMinor($versionArr[$prefix . 'minor']);
        }

        if ($patchPresent) {
            $version->setPatch($versionArr[$prefix . 'patch']);
        }

        $labelName = null;
        $labelVersion = null;
        if ($labelPresent) {
            $labelName = $versionArr[$prefix . 'label'];

            $labelVersion = null;
            if ($labelNumPresent) {
                $labelVersion = $versionArr[$prefix . 'label_num'];
            }
        }

        $version->setLabel($this->labelFactory->get($labelName, $labelVersion));

        return $version;
    }


    /**
     * Validate a version array.
     *
     * @throws InvalidVersionException
     *
     * @param array         $versionArr
     * @param string|null   $prefix
     */
    private function validateVersionArray(array $versionArr, $prefix)
    {
        $majorPresent = array_key_exists($prefix . 'major', $versionArr);
        $minorPresent = array_key_exists($prefix . 'minor', $versionArr) && strlen($versionArr[$prefix . 'minor']);
        $patchPresent = array_key_exists($prefix . 'patch', $versionArr) && strlen($versionArr[$prefix . 'patch']);

        // Handle error case where major version is omitted or a wildcard but minor/patch is a number
        if ((!$majorPresent || $versionArr[$prefix . 'major'] === '*' || $versionArr[$prefix . 'major'] === 'x')
            && (($minorPresent && $versionArr[$prefix . 'minor'] !== '*' && $versionArr[$prefix . 'minor'] !== 'x')
                || ($patchPresent && $versionArr[$prefix . 'patch'] !== '*' && $versionArr[$prefix . 'patch'] !== 'x'))
        ) {
            throw new InvalidVersionException('The version number "' . $versionArr[0] . '" could not be parsed.');
        }

        // Handle error case where minor version is omitted or a wildcard but patch is a number
        if ((!$minorPresent || $versionArr[$prefix . 'minor'] === '*' || $versionArr[$prefix . 'minor'] === 'x')
            && ($patchPresent && $versionArr[$prefix . 'patch'] !== '*' && $versionArr[$prefix . 'patch'] !== 'x')
        ) {
            throw new InvalidVersionException('The version number "' . $versionArr[0] . '" could not be parsed.');
        }
    }
}
