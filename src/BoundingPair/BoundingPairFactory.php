<?php

/**
 * Factory to create BoundingPairs.
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
                    'The bounding pair "' . $versionNo . '" could not be parsed.',
                    $e
                );
            }
        }

        if (is_null($boundingPair) || (is_null($boundingPair->getLower()) && is_null($boundingPair->getUpper()))) {
            throw new InvalidBoundingPairException('The bounding pair "' . $versionNo . '" could not be parsed.');
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
        if ($this->hasArrayElement($boundingPairArr, 'tilde_major')) {
            $boundingPair = $this->getFromTildeArray($boundingPairArr);

        // Single match
        } elseif ($this->hasArrayElement($boundingPairArr, 'single_major')) {
            $boundingPair = $this->getFromSingleArray($boundingPairArr);

        // Hyphenated range match
        } elseif ($this->hasArrayElement($boundingPairArr, 'min_hyphen_major')) {
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

        if ($this->hasArrayElement($minMaxArray, 'min_major')) {
            $boundingPair->setLower($this->comparatorVersionFac->getFromArray($minMaxArray, 'min_'));
        }

        if ($this->hasArrayElement($minMaxArray, 'max_major')) {
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
        if ($this->hasNumericElement($tildeArr, 'tilde_patch')) {
            $lowerArr['tilde_comparator'] = '>=';

            $upperArr['tilde_comparator'] = '<';
            $upperArr['tilde_minor'] = $this->stringPlusOne($upperArr['tilde_minor']);
            $upperArr['tilde_patch'] = '0';

        // Major & Minor tilde match
        } elseif ($this->hasNumericElement($tildeArr, 'tilde_minor')) {
            $lowerArr['tilde_comparator'] = '>=';
            $lowerArr['tilde_patch'] = '0';

            $upperArr['tilde_comparator'] = '<';
            $upperArr['tilde_major'] = $this->stringPlusOne($upperArr['tilde_major']);
            $upperArr['tilde_minor'] = '0';
            $upperArr['tilde_patch'] = '0';

        // Major tilde match
        } else {
            $lowerArr['tilde_comparator'] = '>=';
            $lowerArr['tilde_patch'] = '0';
            $lowerArr['tilde_minor'] = '0';

            $upperArr['tilde_comparator'] = '<';
            $upperArr['tilde_major'] = $this->stringPlusOne($upperArr['tilde_major']);
            $upperArr['tilde_minor'] = '0';
            $upperArr['tilde_patch'] = '0';
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
        $singleArr['single_comparator'] = '=';

        if (!$this->hasNumericElement($singleArr, 'single_patch')) {
            $singleArr['single_patch'] = '0';
        }

        if (!$this->hasNumericElement($singleArr, 'single_minor')) {
            $singleArr['single_minor'] = '0';
        }

        $boundingPair = new BoundingPair();
        $boundingPair
            ->setLower(
                $this->comparatorVersionFac->getFromArray($singleArr, 'single_')
            )
            ->setUpper(
                $this->comparatorVersionFac->getFromArray($singleArr, 'single_')
            );

        return $boundingPair;
    }


    /**
     * Check to ensure that a non-empty element exists for the given array key.
     *
     * @param string[] $arr
     * @param string   $key
     *
     * @return bool
     */
    private function hasArrayElement(array $arr, $key)
    {
        return array_key_exists($key, $arr) && strlen($arr[$key]);
    }


    /**
     * Check to ensure that a numeric element exists for the given array key.
     *
     * @param string[] $arr
     * @param string   $key
     *
     * @return bool
     */
    private function hasNumericElement(array $arr, $key)
    {
        return $this->hasArrayElement($arr, $key) && is_numeric($arr[$key]);
    }


    /**
     * Safely increment a number stored as a string by one, returning a string representation of the new number.
     *
     * @todo Test for integer overflow - possibly not here
     *
     * @param string $val
     *
     * @return string
     */
    private function stringPlusOne($val)
    {
        $intVal = intval($val);
        $intVal++;
        return strval($intVal);
    }
}
