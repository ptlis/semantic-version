<?php

/**
 * Class representing the development release labels.
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
 * Class representing the development release label.
 */
class LabelDev extends AbstractNamedLabel
{
    /**
     * @var string
     */
    private $name;

    /**
     * Set the label name
     *
     * @param string $name
     *
     * @return LabelDev
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Get the label name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Lowest precedence.
     *
     * @return int
     */
    public function getPrecedence()
    {
        return 1;
    }
}
