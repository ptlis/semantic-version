<?php

/**
 * Factory to create BoundingPairs.
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

namespace ptlis\SemanticVersion\BoundingPair;

use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersionFactory;
use ptlis\SemanticVersion\Exception\InvalidBoundingPairException;
use ptlis\SemanticVersion\Exception\InvalidComparatorVersionException;

/**
 * Factory to create BoundingPairs.
 */
class BoundingPairFactory
{
    /**
     * @var BoundingPairRegexProviderInterface
     */
    private $regexProvider;

    /**
     * @var ComparatorVersionFactory
     */
    private $comparatorVersionFac;


    /**
     * Constructor
     *
     * @param BoundingPairRegexProviderInterface    $regexProvider
     * @param ComparatorVersionFactory              $comparatorVersionFac
     */
    public function __construct(
        BoundingPairRegexProviderInterface $regexProvider,
        ComparatorVersionFactory $comparatorVersionFac
    ) {
        $this->regexProvider = $regexProvider;
        $this->comparatorVersionFac = $comparatorVersionFac;
    }


    /**
     * Parse a string version number & return a Version object.
     *
     * @throws InvalidBoundingPairException
     *
     * @param string $versionNo
     *
     * @return BoundingPair
     */
    public function parse($versionNo)
    {
        $boundingPair = null;

        if (preg_match($this->regexProvider->getBoundingPair(), $versionNo, $matches)) {
            try {
                $boundingPair = $this->getFromArray($matches);

            } catch (InvalidComparatorVersionException $e) {
                throw new InvalidBoundingPairException(
                    'The bounding pair "' . $versionNo . '" could not be parsed.1',
                    $e
                );
            }
        }

        if (is_null($boundingPair) || (is_null($boundingPair->getLower()) && is_null($boundingPair->getUpper()))) {
            throw new InvalidBoundingPairException('The bounding pair "' . $versionNo . '" could not be parsed.2');
        }

        return $boundingPair;
    }


    /**
     * Create a bounding pair from the provided array.
     *
     * @throws InvalidComparatorVersionException
     *
     * @param string[] $boundingPairArr
     *
     * @return BoundingPair
     */
    public function getFromArray(array $boundingPairArr)
    {
        // Tilde match
        if (array_key_exists('tilde_major', $boundingPairArr) && strlen($boundingPairArr['tilde_major'])) {
            $boundingPair = $this->getFromTildeArray($boundingPairArr);

        // Single match
        } elseif (array_key_exists('single_major', $boundingPairArr) && strlen($boundingPairArr['single_major'])) {
            $boundingPair = $this->getFromSingleArray($boundingPairArr);

        // Hyphenated range match
        } elseif (array_key_exists('min_hyphen_major', $boundingPairArr)
                && strlen($boundingPairArr['min_hyphen_major'])) {
            $boundingPair = $this->getFromHyphenatedArray($boundingPairArr);

        // Min-max match
        } else {
            $boundingPair = $this->getFromMinMaxArray($boundingPairArr);
        }

        return $boundingPair;
    }


    /**
     * Create a bounding pair from the provided min-max array.
     *
     * @throws InvalidComparatorVersionException
     *
     * @param array $minMaxArray
     *
     * @return BoundingPair
     */
    private function getFromMinMaxArray(array $minMaxArray)
    {
        $boundingPair = new BoundingPair();

        if (array_key_exists('min_major', $minMaxArray) && strlen($minMaxArray['min_major'])) {
            $boundingPair->setLower($this->comparatorVersionFac->getFromArray($minMaxArray, 'min_'));
        }

        if (array_key_exists('max_major', $minMaxArray) && strlen($minMaxArray['max_major'])) {
            $boundingPair->setUpper($this->comparatorVersionFac->getFromArray($minMaxArray, 'max_'));
        }

        return $boundingPair;
    }


    /**
     * Create a bounding pair from the provided tilde array.
     *
     * @throws InvalidComparatorVersionException
     *
     * @param string[] $tildeArr
     *
     * @return BoundingPair
     */
    private function getFromTildeArray(array $tildeArr)
    {
        $upperArr = $tildeArr;
        $lowerArr = $tildeArr;

        // Full version tilde match
        if (array_key_exists('tilde_patch', $tildeArr) && is_numeric($tildeArr['tilde_patch'])) {
            $lowerArr['tilde_comparator'] = '>=';

            $upperArr['tilde_comparator'] = '<';
            $upperArr['tilde_minor']++;
            $upperArr['tilde_patch'] = 0;

            // Major & Minor tilde match
        } elseif (array_key_exists('tilde_minor', $tildeArr) && is_numeric($tildeArr['tilde_minor'])) {
            $lowerArr['tilde_comparator'] = '>=';
            $lowerArr['tilde_patch'] = 0;

            $upperArr['tilde_comparator'] = '<';
            $upperArr['tilde_major']++;
            $upperArr['tilde_minor'] = 0;
            $upperArr['tilde_patch'] = 0;

            // Major tilde match
        } else {
            $lowerArr['tilde_comparator'] = '>=';
            $lowerArr['tilde_patch'] = 0;
            $lowerArr['tilde_minor'] = 0;

            $upperArr['tilde_comparator'] = '<';
            $upperArr['tilde_major']++;
            $upperArr['tilde_minor'] = 0;
            $upperArr['tilde_patch'] = 0;
        }

        $boundingPair = new BoundingPair();
        $boundingPair
            ->setLower(
                $this->comparatorVersionFac->getFromArray($lowerArr, 'tilde_')
            )
            ->setUpper(
                $this->comparatorVersionFac->getFromArray($upperArr, 'tilde_')
            );

        return $boundingPair;
    }


    /**
     * Create a bounding pair for a hyphenated pair array.
     *
     * @throws InvalidComparatorVersionException
     *
     * @param string[] $boundingPairArr
     *
     * @return BoundingPair
     */
    private function getFromHyphenatedArray(array $boundingPairArr)
    {
        $boundingPair = new BoundingPair();

        $boundingPairArr['min_hyphen_comparator'] = '>=';
        $boundingPairArr['max_hyphen_comparator'] = '<';

        $boundingPair
            ->setLower(
                $this->comparatorVersionFac->getFromArray($boundingPairArr, 'min_hyphen_')
            )
            ->setUpper(
                $this->comparatorVersionFac->getFromArray($boundingPairArr, 'max_hyphen_')
            );

        return $boundingPair;
    }


    /**
     * Get a bounding pair from the provided single version array.
     *
     * @param array $singleArr
     *
     * @return BoundingPair
     */
    private function getFromSingleArray(array $singleArr)
    {
        $upperArr = $singleArr;
        $lowerArr = $singleArr;

        // Fully qualified version
        if (array_key_exists('single_patch', $singleArr) && is_numeric($singleArr['single_patch'])) {
            $lowerArr['single_comparator'] = '=';

            $upperArr['single_comparator'] = '=';

        // Minor - range (minor inc by 1)
        } elseif (array_key_exists('single_minor', $singleArr) && is_numeric($singleArr['single_minor'])) {
            $lowerArr['single_comparator'] = '>=';
            $lowerArr['single_patch'] = 0;

            $upperArr['single_comparator'] = '<';
            $upperArr['single_minor']++;
            $upperArr['single_patch'] = 0;

        // Major - range (major inc by 1;
        } else {
            $lowerArr['single_comparator'] = '>=';
            $lowerArr['single_minor'] = 0;
            $lowerArr['single_patch'] = 0;

            $upperArr['single_comparator'] = '<';
            $upperArr['single_major']++;
            $upperArr['single_minor'] = 0;
            $upperArr['single_patch'] = 0;
        }

        $boundingPair = new BoundingPair();
        $boundingPair
            ->setLower(
                $this->comparatorVersionFac->getFromArray($lowerArr, 'single_')
            )
            ->setUpper(
                $this->comparatorVersionFac->getFromArray($upperArr, 'single_')
            );

        return $boundingPair;
    }
}
