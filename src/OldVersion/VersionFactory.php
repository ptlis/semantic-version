<?php

/**
 * Factory to create Versions.
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

namespace ptlis\SemanticVersion\OldVersion;

use ptlis\SemanticVersion\Exception\InvalidVersionException;
use ptlis\SemanticVersion\Label\LabelFactory;
use ptlis\SemanticVersion\Label\LabelInterface;

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
     * @param string $versionNo
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
     * Get a Version object for the provided options.
     *
     * @throws InvalidVersionException
     *
     * @param int                   $major
     * @param int|null              $minor
     * @param int|null              $patch
     * @param LabelInterface|null   $label
     *
     * @return Version
     */
    public function get($major, $minor = null, $patch = null, LabelInterface $label = null)
    {
        if (!strlen($minor)) {
            $minor = 0;
            $patch = 0;
        }

        if (!strlen($patch)) {
            $patch = 0;
        }

        if (is_null($label)) {
            $label = $this->labelFactory->get('');
        }

        $version = new Version();
        $version
            ->setMajor($major)
            ->setMinor($minor)
            ->setPatch($patch)
            ->setLabel($label);

        return $version;
    }


    /**
     * Create a Version object from the provided array of data
     *
     * @throws InvalidVersionException
     *
     * @param string[]       $versionArr
     * @param string|null   $prefix
     *
     * @return Version
     */
    public function getFromArray(array $versionArr, $prefix = null)
    {
        $this->validateVersionArray($versionArr, $prefix);

        $version = new Version();
        $version = $this->setVersionNumber($version, $versionArr, $prefix);
        $version = $this->setVersionLabel($version, $versionArr, $prefix);

        return $version;
    }


    /**
     * Extracts the version number from the array & and into the version object.
     *
     * @throws InvalidVersionException
     *
     * @param VersionInterface $version
     * @param string[]         $versionArr
     * @param string           $prefix
     *
     * @return VersionInterface
     */
    private function setVersionNumber(VersionInterface $version, array $versionArr, $prefix)
    {
        try {
            $version->setMajor($versionArr[$prefix . 'major']);

            if ($this->dataAtIndex($versionArr, $prefix . 'minor')) {
                $version->setMinor($versionArr[$prefix . 'minor']);
            }

            if ($this->dataAtIndex($versionArr, $prefix . 'patch')) {
                $version->setPatch($versionArr[$prefix . 'patch']);
            }
        } catch (InvalidVersionException $e) {
            throw new InvalidVersionException(
                'The version number "' . $versionArr[0] . '" could not be parsed.',
                $e
            );
        }

        return $version;
    }


    /**
     * Extracts the version label from the array & and into the version object.
     *
     * @param VersionInterface $version
     * @param string[]         $versionArr
     * @param string           $prefix
     *
     * @return VersionInterface
     */
    private function setVersionLabel(VersionInterface $version, array $versionArr, $prefix)
    {
        $labelName = null;
        $labelVersion = null;
        $labelMetadata = null;
        if ($this->dataAtIndex($versionArr, $prefix . 'label')) {
            $labelName = $versionArr[$prefix . 'label'];

            if ($this->dataAtIndex($versionArr, $prefix . 'label_num')) {
                $labelVersion = $versionArr[$prefix . 'label_num'];
            }

            if ($this->dataAtIndex($versionArr, $prefix . 'label_meta')) {
                $labelMetadata = $versionArr[$prefix . 'label_meta'];
            }
        }

        $version->setLabel($this->labelFactory->get($labelName, $labelVersion, $labelMetadata));

        return $version;
    }


    /**
     * Returns true if the element identified by $index has data.
     *
     * @param string[]  $versionArr
     * @param string    $index
     *
     * @return bool
     */
    private function dataAtIndex(array $versionArr, $index)
    {
        $valid = false;

        if (array_key_exists($index, $versionArr) && strlen($versionArr[$index])) {
            $valid = true;
        }

        return $valid;
    }


    /**
     * Validate a version array.
     *
     * @throws InvalidVersionException
     *
     * @param string[]      $versionArr
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
     * @param string[]  $versionArr
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
     * @param string[]  $versionArr
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
