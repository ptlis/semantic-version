<?php

/**
 * Factory to create comparators.
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

namespace ptlis\SemanticVersion\Comparator;

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
    private $comparatorList = [
        '='     => 'ptlis\SemanticVersion\Comparator\EqualTo',
        '>='    => 'ptlis\SemanticVersion\Comparator\GreaterOrEqualTo',
        '>'     => 'ptlis\SemanticVersion\Comparator\GreaterThan',
        '<='    => 'ptlis\SemanticVersion\Comparator\LessOrEqualTo',
        '<'     => 'ptlis\SemanticVersion\Comparator\LessThan',
    ];


    /**
     * Adds a label type to factory.
     *
     * @throws \RuntimeException
     *
     * @param $type
     * @param $class
     */
    public function addType($type, $class)
    {
        if (!class_exists($class)) {
            throw new \RuntimeException(
                'The class "' . $class . '" does not exist'
            );
        }

        // TODO: check for implements interface

        $this->comparatorList[$type] = $class;
    }


    /**
     * Remove a label type from factory.
     *
     * @param $type
     */
    public function removeType($type)
    {
        unset($this->comparatorList[$type]);
    }


    /**
     *
     *
     * @throws \RuntimeException
     *
     * @param array $comparatorList
     */
    public function setTypeList(array $comparatorList)
    {
        $this->clearTypeList();
        foreach ($comparatorList as $type => $class) {
            $this->addType($type, $class);
        }
    }


    /**
     * Clears the type list.
     */
    public function clearTypeList()
    {
        $this->comparatorList = [];
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
