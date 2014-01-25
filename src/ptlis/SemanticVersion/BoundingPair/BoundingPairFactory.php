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

use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersion;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersionFactory;
use ptlis\SemanticVersion\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\Exception\InvalidBoundingPairException;
use ptlis\SemanticVersion\Exception\InvalidComparatorVersionException;
use ptlis\SemanticVersion\Version\VersionFactory;

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
     * @var VersionFactory
     */
    private $versionFac;

    /**
     * @var ComparatorFactory
     */
    private $comparatorFac;

    /**
     * Constructor
     *
     * @param BoundingPairRegexProviderInterface    $regexProvider
     * @param ComparatorVersionFactory              $comparatorVersionFac
     * @param VersionFactory                        $versionFac
     * @param ComparatorFactory                     $comparatorFac
     */
    public function __construct(
        BoundingPairRegexProviderInterface $regexProvider,
        ComparatorVersionFactory $comparatorVersionFac,
        VersionFactory $versionFac,
        ComparatorFactory $comparatorFac
    ) {
        $this->regexProvider = $regexProvider;
        $this->comparatorVersionFac = $comparatorVersionFac;
        $this->versionFac = $versionFac;
        $this->comparatorFac = $comparatorFac;
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
        if (preg_match($this->regexProvider->getBoundingPair(), $versionNo, $matches)) {
            $versionRange = new BoundingPair();

            try {

                if (array_key_exists('tilde_major', $matches) && strlen($matches['tilde_major'])) {
                    $versionRange = $this->getFromSingleArray($matches);

                } elseif (array_key_exists('min_hyphen_major', $matches) && strlen($matches['min_hyphen_major'])) {
                    $lower = new ComparatorVersion();
                    $lower
                        ->setVersion($this->versionFac->getFromArray($matches, 'min_hyphen_'))
                        ->setComparator(new GreaterOrEqualTo());

                    $upper = new ComparatorVersion();
                    $upper
                        ->setVersion($this->versionFac->getFromArray($matches, 'max_hyphen_'))
                        ->setComparator(new LessThan());

                    $versionRange->setLower($lower);
                    $versionRange->setUpper($upper);

                } else {

                    if (array_key_exists('min_major', $matches) && strlen($matches['min_major'])) {
                        $versionRange->setLower($this->comparatorVersionFac->getFromArray($matches, 'min_'));
                    }

                    if (array_key_exists('max_major', $matches) && strlen($matches['max_major'])) {
                        $versionRange->setUpper($this->comparatorVersionFac->getFromArray($matches, 'max_'));
                    }
                }

            } catch (InvalidComparatorVersionException $e) {
                throw new InvalidBoundingPairException(
                    'The bounding pair "' . $versionNo . '" could not be parsed.',
                    $e
                );
            }

            if (is_null($versionRange->getLower()) && is_null($versionRange->getUpper())) {
                throw new InvalidBoundingPairException('The bounding pair "' . $versionNo . '" could not be parsed.');
            }

        } else {
            throw new InvalidBoundingPairException('The bounding pair "' . $versionNo . '" could not be parsed.');
        }

        return $versionRange;
    }


    public function getFromSingleArray($versionRangeArr)
    {
        $versionRange = new BoundingPair();

        // Tilde match
        if (array_key_exists('tilde', $versionRangeArr) && strlen($versionRangeArr['tilde'])) {

            // Full version tilde match
            if (array_key_exists('tilde_patch', $versionRangeArr) && is_numeric($versionRangeArr['tilde_patch'])) {
                $lower = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'tilde_');
                $lower->setComparator($this->comparatorFac->get('>='));

                $upper = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'tilde_');
                $upper->getVersion()->setMinor($upper->getVersion()->getMinor() + 1);
                $upper->getVersion()->setPatch(0);
                $upper->setComparator($this->comparatorFac->get('<'));

            // Major & Minor tilde match
            } elseif (array_key_exists('tilde_minor', $versionRangeArr)
                    && is_numeric($versionRangeArr['tilde_minor'])) {
                $lower = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'tilde_');
                $lower->getVersion()->setPatch(0);
                $lower->setComparator($this->comparatorFac->get('>='));

                $upper = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'tilde_');
                $upper->getVersion()->setMajor($upper->getVersion()->getMajor() + 1);
                $upper->getVersion()->setMinor(0);
                $upper->getVersion()->setPatch(0);
                $upper->setComparator($this->comparatorFac->get('<'));

                // Major tilde match
            } else {
                $lower = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'tilde_');
                $lower->getVersion()->setMinor(0);
                $lower->getVersion()->setPatch(0);
                $lower->setComparator($this->comparatorFac->get('>='));

                $upper = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'tilde_');
                $upper->getVersion()->setMajor($upper->getVersion()->getMajor() + 1);
                $upper->getVersion()->setMinor(0);
                $upper->getVersion()->setPatch(0);
                $upper->setComparator($this->comparatorFac->get('<'));
            }

            // Label & patch - exact match
        } elseif (array_key_exists('tilde_patch', $versionRangeArr) && strlen($versionRangeArr['tilde_patch'])) {
            $lower = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'tilde_');
            $lower->setComparator($this->comparatorFac->get('='));

            $upper = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'tilde_');
            $upper->setComparator($this->comparatorFac->get('='));

            // Minor - range (minor inc by 1)
        } elseif (array_key_exists('tilde_minor', $versionRangeArr) && strlen($versionRangeArr['tilde_minor'])) {
            $lower = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'tilde_');
            $lower->setComparator($this->comparatorFac->get('>='));

            $upper = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'tilde_');
            $upper->getVersion()->setMinor($upper->getVersion()->getMinor() + 1);
            $upper->getVersion()->setPatch(0);
            $upper->setComparator($this->comparatorFac->get('<'));

            // Major - range (major inc by 1;
        } else {
            $lower = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'tilde_');
            $lower->setComparator($this->comparatorFac->get('>='));

            $upper = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'tilde_');
            $upper->getVersion()->setMajor($upper->getVersion()->getMajor() + 1);
            $upper->getVersion()->setMinor(0);
            $upper->getVersion()->setPatch(0);
            $upper->setComparator($this->comparatorFac->get('<'));
        }

        if (!is_null($lower->getComparator())) {
            $versionRange->setLower($lower);
        }

        if (!is_null($upper->getComparator())) {
            $versionRange->setUpper($upper);
        }

        return $versionRange;
    }
}
