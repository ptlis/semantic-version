<?php

/**
 * Class representing the release candidate label.
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
 * Class representing the release candidate label.
 */
class LabelRc extends AbstractNamedLabel
{
    /**
     * Get the label name.
     *
     * @return string
     */
    public function getName()
    {
        return 'rc';
    }


    /**
     * Second greatest precedence.
     *
     * @return int
     */
    public function getPrecedence()
    {
        return 3;
    }
}