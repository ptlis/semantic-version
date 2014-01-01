<?php

/**
 * Exception thrown when the engine is unable to parse a version range.
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

namespace ptlis\SemanticVersion\Exception;

/**
 * Exception thrown when the engine is unable to parse a version range.
 */
class InvalidVersionRangeException extends \RuntimeException
{
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, null, $previous);
    }
}
