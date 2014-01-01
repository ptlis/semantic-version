<?php

/**
 * Factory to create ComparatorVersions.
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

namespace ptlis\SemanticVersion\ComparatorVersion;

use ptlis\SemanticVersion\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Exception\InvalidComparatorVersionException;
use ptlis\SemanticVersion\Exception\InvalidVersionException;
use ptlis\SemanticVersion\Version\VersionFactory;

/**
 * Factory to create ComparatorVersions.
 */
class ComparatorVersionFactory
{
    /**
     * @var ComparatorVersionRegexProviderInterface
     */
    private $regexProvider;

    /**
     * @var VersionFactory
     */
    private $versionFactory;


    /**
     * Constructor
     *
     * @param ComparatorVersionRegexProviderInterface $regexProvider
     * @param VersionFactory                          $versionFactory
     */
    public function __construct(
        ComparatorVersionRegexProviderInterface $regexProvider,
        VersionFactory $versionFactory
    ) {
        $this->regexProvider = $regexProvider;
        $this->versionFactory = $versionFactory;
    }


    /**
     * Parse a string version number & return a Version object.
     *
     * @throws InvalidComparatorVersionException
     *
     * @param $versionNo
     *
     * @return ComparatorVersion
     */
    public function parse($versionNo)
    {
        if (preg_match($this->regexProvider->getComparatorVersion(), $versionNo, $matches)) {
            $comparatorVersion = $this->getFromArray($matches);
        } else {
            throw new InvalidComparatorVersionException(
                'The comparator version "' . $versionNo . '" could not be parsed.'
            );
        }

        return $comparatorVersion;
    }


    /**
     * Create a Version object from the provided array of data
     *
     * @throws InvalidComparatorVersionException
     *
     * @param array         $comparatorVersionArr
     * @param string|null   $prefix
     *
     * @return ComparatorVersion
     */
    public function getFromArray(array $comparatorVersionArr, $prefix = null)
    {
        $comparatorFactory = new ComparatorFactory();   // TODO: Inject!

        $comparatorVersion = new ComparatorVersion();
        try {
            $version = $this->versionFactory->getFromArray($comparatorVersionArr, $prefix);
        } catch (InvalidVersionException $e) {
            throw new InvalidComparatorVersionException(
                'The comparator version "' . $comparatorVersionArr[0] . '" could not be parsed.',
                $e
            );
        }

        $comparatorVersion
            ->setVersion($version);

        // A comparator has been found
        if (array_key_exists($prefix . 'comparator', $comparatorVersionArr)
                && strlen($comparatorVersionArr[$prefix . 'comparator'])) {
            $comparatorVersion->setComparator($comparatorFactory->get($comparatorVersionArr[$prefix . 'comparator']));
        }


        return $comparatorVersion;
    }
}
