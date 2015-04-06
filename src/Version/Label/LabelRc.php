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
 * Convenience class, implementation of release candidate label.
 */
class LabelRc extends Label
{
    /**
     * Constructor.
     *
     * @param int|null $version
     * @param string $buildMetadata
     */
    public function __construct($version = null, $buildMetadata = '')
    {
        parent::__construct(Label::PRECEDENCE_RC, 'rc', $version, $buildMetadata);
    }
}
