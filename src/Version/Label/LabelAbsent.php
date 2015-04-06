<?php

/**
 * PHP Version 5.3
 *
 * @copyright   (c) 2014-2015 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Version\Label;

/**
 * Convenience class, implementation of absent label.
 */
class LabelAbsent extends Label
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(Label::PRECEDENCE_ABSENT);
    }
}
