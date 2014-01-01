<?php

/**
 * Factory to create comparators.
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

namespace ptlis\SemanticVersion\Entity\Comparator;

use ptlis\SemanticVersion\Exception\InvalidComparatorException;

/**
 * Factory to create comparators.
 */
class ComparatorFactory
{
    /**
     * Mapping of comparator symbols to classes.
     *
     * @var array
     */
    private $comparatorList;


    /**
     * Constructor.
     *
     * @param array|null $comparatorList Override default comparators.
     */
    public function __construct(array $comparatorList = null)
    {
        // Override defaults
        if (!is_null($comparatorList) && is_array($comparatorList) && count($comparatorList)) {
            $this->comparatorList = $comparatorList;

        // Keep defaults
        } else {
            $this->comparatorList = [
                '='     => 'ptlis\SemanticVersion\Entity\Comparator\EqualTo',
                '>='    => 'ptlis\SemanticVersion\Entity\Comparator\GreaterOrEqualTo',
                '>'     => 'ptlis\SemanticVersion\Entity\Comparator\GreaterThan',
                '<='    => 'ptlis\SemanticVersion\Entity\Comparator\LessOrEqualTo',
                '<'     => 'ptlis\SemanticVersion\Entity\Comparator\LessThan',
            ];
        }
    }


    /**
     * Get a comparator class from the comparator symbol.
     *
     * @throws InvalidComparatorException
     *
     * @param string $symbol
     *
     * @return ComparatorInterface
     */
    public function get($symbol)
    {
        if (!array_key_exists($symbol, $this->comparatorList)) {
            throw new InvalidComparatorException('The provided comparator "' . $symbol . '" is invalid.');
        }

        return new $this->comparatorList[$symbol]();
    }
}
