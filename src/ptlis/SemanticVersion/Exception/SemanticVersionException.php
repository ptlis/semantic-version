<?php

/**
 * Base exception type for library.
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

namespace ptlis\SemanticVersion\Exception;

/**
 * Base exception type for library.
 */
class SemanticVersionException extends \RuntimeException
{
    /**
     * Constructor
     *
     * @param string            $message
     * @param \Exception|null   $previous
     */
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, null, $previous);
    }
}
