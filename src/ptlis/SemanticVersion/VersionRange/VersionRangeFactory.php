<?php

/**
 * Factory to create VersionRanges.
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

namespace ptlis\SemanticVersion\VersionRange;

use ptlis\SemanticVersion\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersionFactory;
use ptlis\SemanticVersion\Exception\InvalidComparatorVersionException;
use ptlis\SemanticVersion\Exception\InvalidVersionRangeException;

/**
 * Factory to create VersionRanges.
 */
class VersionRangeFactory
{
    /**
     * @var VersionRangeRegexProviderInterface
     */
    private $regexProvider;

    /**
     * @var ComparatorVersionFactory
     */
    private $comparatorVersionFac;

    /**
     * Constructor
     *
     * @param VersionRangeRegexProviderInterface $regexProvider
     * @param ComparatorVersionFactory           $comparatorVersionFac
     */
    public function __construct(
        VersionRangeRegexProviderInterface $regexProvider,
        ComparatorVersionFactory $comparatorVersionFac
    ) {
        $this->regexProvider = $regexProvider;
        $this->comparatorVersionFac = $comparatorVersionFac;
    }


    /**
     * Parse a string version number & return a Version object.
     *
     * @throws InvalidVersionRangeException
     *
     * @param $versionNo
     *
     * @return VersionRange
     */
    public function parse($versionNo)
    {
        if (preg_match($this->regexProvider->getVersionRange(), $versionNo, $matches)) {
            $versionRange = new VersionRange();

            try {
                if (array_key_exists('min_major', $matches) && strlen($matches['min_major'])) {
                    $versionRange->setLower($this->comparatorVersionFac->getFromArray($matches, 'min_'));
                }

                if (array_key_exists('max_major', $matches) && strlen($matches['max_major'])) {
                    $versionRange->setUpper($this->comparatorVersionFac->getFromArray($matches, 'max_'));
                }

                if (array_key_exists('single_major', $matches) && strlen($matches['single_major'])) {
                    $versionRange = $this->getFromSingleArray($matches);
                }

            } catch (InvalidComparatorVersionException $e) {
                throw new InvalidVersionRangeException(
                    'The version range "' . $versionNo . '" could not be parsed.',
                    $e
                );

            }

            if (is_null($versionRange->getLower()) && is_null($versionRange->getUpper())) {
                throw new InvalidVersionRangeException('The version range "' . $versionNo . '" could not be parsed.');
            }

        } else {
            throw new InvalidVersionRangeException('The version range "' . $versionNo . '" could not be parsed.');
        }

        return $versionRange;
    }


    public function getFromSingleArray($versionRangeArr)
    {
        $comparatorFactory = new ComparatorFactory(); // TODO: Inject

        $versionRange = new VersionRange();

        // Tilde match
        if (array_key_exists('single_tilde', $versionRangeArr) && strlen($versionRangeArr['single_tilde'])) {

            // Full version tilde match
            if (array_key_exists('single_patch', $versionRangeArr) && is_numeric($versionRangeArr['single_patch'])) {
                $lower = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'single_');
                $lower->setComparator($comparatorFactory->get('>='));

                $upper = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'single_');
                $upper->getVersion()->setMinor($upper->getVersion()->getMinor() + 1);
                $upper->getVersion()->setPatch(0);
                $upper->setComparator($comparatorFactory->get('<'));

                // Major & Minor tilde match
            } elseif (array_key_exists('single_minor', $versionRangeArr)
                    && is_numeric($versionRangeArr['single_minor'])) {
                $lower = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'single_');
                $lower->getVersion()->setPatch(0);
                $lower->setComparator($comparatorFactory->get('>='));

                $upper = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'single_');
                $upper->getVersion()->setMajor($upper->getVersion()->getMajor() + 1);
                $upper->getVersion()->setMinor(0);
                $upper->getVersion()->setPatch(0);
                $upper->setComparator($comparatorFactory->get('<'));

                // Major tilde match
            } else {
                $lower = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'single_');
                $lower->getVersion()->setMinor(0);
                $lower->getVersion()->setPatch(0);
                $lower->setComparator($comparatorFactory->get('>='));

                $upper = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'single_');
                $upper->getVersion()->setMajor($upper->getVersion()->getMajor() + 1);
                $upper->getVersion()->setMinor(0);
                $upper->getVersion()->setPatch(0);
                $upper->setComparator($comparatorFactory->get('<'));
            }

            // Label & patch - exact match
        } elseif (array_key_exists('single_patch', $versionRangeArr) && strlen($versionRangeArr['single_patch'])) {
            $lower = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'single_');
            $lower->setComparator($comparatorFactory->get('='));

            $upper = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'single_');
            $upper->setComparator($comparatorFactory->get('='));

            // Minor - range (minor inc by 1)
        } elseif (array_key_exists('single_minor', $versionRangeArr) && strlen($versionRangeArr['single_minor'])) {
            $lower = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'single_');
            $lower->setComparator($comparatorFactory->get('>='));

            $upper = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'single_');
            $upper->getVersion()->setMinor($upper->getVersion()->getMinor() + 1);
            $upper->getVersion()->setPatch(0);
            $upper->setComparator($comparatorFactory->get('<'));

            // Major - range (major inc by 1;
        } else {
            $lower = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'single_');
            $lower->setComparator($comparatorFactory->get('>='));

            $upper = $this->comparatorVersionFac->getFromArray($versionRangeArr, 'single_');
            $upper->getVersion()->setMajor($upper->getVersion()->getMajor() + 1);
            $upper->getVersion()->setMinor(0);
            $upper->getVersion()->setPatch(0);
            $upper->setComparator($comparatorFactory->get('<'));
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
