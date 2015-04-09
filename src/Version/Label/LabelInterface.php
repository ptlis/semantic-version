<?php

/**
 * PHP Version 5.3
 *
 * @copyright   (c) 2015 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Version\Label;

/**
 * Interface that label value types must implement.
 */
interface LabelInterface
{
    /**
     * Precedence for development releases.
     */
    const PRECEDENCE_DEV = 0;

    /**
     * Precedence for alpha releases.
     */
    const PRECEDENCE_ALPHA = 1;

    /**
     * Precedence for beta releases.
     */
    const PRECEDENCE_BETA = 2;

    /**
     * Precedence for release candidates.
     */
    const PRECEDENCE_RC = 3;

    /**
     * Precedence for absent labels.
     */
    const PRECEDENCE_ABSENT = 4;


    /**
     * Get the precedence value for the label ('dev' (0) < alpha (1) < beta (2) < rc (3) < absent (4)); greater values are closer to
     *  release 'i.e. later'.
     *
     * @return int
     */
    public function getPrecedence();

    /**
     * Get the label name (eg 'alpha')
     *
     * @return string
     */
    public function getName();

    /**
     * Get the label version number
     *
     * @return int|null
     */
    public function getVersion();

    /**
     * Get the build metadata for the label.
     *
     * See Section 10 @ http://semver.org/ for details of what constitutes build metadata
     *
     * @return string
     */
    public function getBuildMetaData();

    /**
     * Return a string representation of the label.
     *
     * @return string
     */
    public function __toString();
}
