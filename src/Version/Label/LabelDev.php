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
 * Convenience class, implementation of development label.
 */
class LabelDev extends Label
{
    /**
     * Constructor.
     *
     * @param string $name
     * @param int|null $version
     * @param string $buildMetadata
     */
    public function __construct($name, $version = null, $buildMetadata = null)
    {
        parent::__construct(Label::PRECEDENCE_DEV, $name, $version, $buildMetadata);
    }
}
