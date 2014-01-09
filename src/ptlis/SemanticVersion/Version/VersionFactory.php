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
        $majorKey = $prefix . 'major';
        $minorKey = $prefix . 'minor';
        $patchKey = $prefix . 'patch';

        // Handle error case where major version is omitted or a wildcard but minor/patch is a number
        if ($this->omittedOrWildcard($versionArr, $majorKey)
            && ($this->presentNotWildcard($versionArr, $minorKey) || $this->presentNotWildcard($versionArr, $patchKey))
        ) {
            throw new InvalidVersionException('The version number "' . $versionArr[0] . '" could not be parsed.');
        }

        // Handle error case where minor version is omitted or a wildcard but patch is a number
        if ($this->omittedOrWildcard($versionArr, $minorKey) && $this->presentNotWildcard($versionArr, $patchKey)) {
            throw new InvalidVersionException('The version number "' . $versionArr[0] . '" could not be parsed.');
        }
    }


    /**
     * Returns true if the version value identified by key was omitted or is a wildcard.
     *
     * @param array     $versionArr
     * @param string    $key
     *
     * @return bool
     */
    private function omittedOrWildcard($versionArr, $key)
    {
        return !(array_key_exists($key, $versionArr) && strlen($versionArr[$key]))
            || ($versionArr[$key] === '*' || $versionArr[$key] === 'x');
    }


    /**
     * Returns true if the version value identified by key was present and is not a wildcard.
     *
     * @param array     $versionArr
     * @param string    $key
     *
     * @return bool
     */
    private function presentNotWildcard($versionArr, $key)
    {
        return array_key_exists($key, $versionArr) && strlen($versionArr[$key])
            && $versionArr[$key] !== '*' && $versionArr[$key] !== 'x';
    }
}
