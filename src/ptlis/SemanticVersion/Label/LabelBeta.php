<?php

/**
 * Class representing the beta label.
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
 * Class representing the beta label.
 */
class LabelBeta extends AbstractNamedLabel
{
    /**
     * Get the label name.
     *
     * @return string
     */
    public function getName()
    {
        return 'beta';
    }


    /**
     * Third lowest precedence.
     *
     * @return int
     */
    public function getPrecedence()
    {
        return 3;
    }
}
