<?php

/**
 * Interface that sortable collections must implement.
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

namespace ptlis\SemanticVersion\Collection;

/**
 * Interface that sortable collections must implement.
 */
interface SortableCollectionInterface extends CollectionInterface
{
    /**
     * Set the internal store to the provided values.
     *
     * @param array $versionList
     */
    public function setList(array $versionList);


    /**
     * Returns a new sorted collection.
     *
     * @return SortableCollectionInterface with elements in ascending order
     */
    public function getAscending();


    /**
     * Returns a new sorted collection.
     *
     * @return SortableCollectionInterface with elements in descending order
     */
    public function getDescending();
}
