<?php

/**
 * Interface that wildcard version labels must implement.
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

namespace ptlis\SemanticVersion\Label;

/**
 * Interface that wildcard version labels must implement.
 */
interface WildcardLabelInterface extends LabelInterface
{
    /**
     * Sets the label name.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function setName($name);
}
