<?php

/**
 * Exception thrown when the engine is unable to parse a version number.
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
 * Exception thrown when the engine is unable to parse a version number.
 */
class InvalidVersionException extends \RuntimeException
{
}
