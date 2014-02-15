<?php

/**
 * Interface that present version labels must implement.
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

namespace ptlis\SemanticVersion\Label;

/**
 * Interface that present version labels must implement.
 */
interface LabelPresentInterface extends LabelInterface
{
    /**
     * Set the label version number;
     *
     * @param int|null $version
     *
     * @return LabelPresentInterface
     */
    public function setVersion($version);


    /**
     * Set the build metadata for the label.
     *
     * @param string $metadata
     *
     * @return LabelPresentInterface
     */
    public function setBuildMetaData($metadata);
}
