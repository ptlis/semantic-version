<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Comparator;

use ptlis\SemanticVersion\Parse\Token;

/**
 * Factory to build comparator instances from string representation.
 */
final class ComparatorFactory
{
    /**
     * @var ComparatorInterface[]
     */
    private $comparatorStringMap;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->comparatorStringMap = [
            '>' => new GreaterThan(),
            '>=' => new GreaterOrEqualTo(),
            '<' => new LessThan(),
            '<=' => new LessOrEqualTo(),
            '=' => new EqualTo()
        ];
    }

    /**
     * Build a comparator instance from it's string representation
     *
     * @param string $comparatorString
     *
     * @return ComparatorInterface|null
     */
    public function get($comparatorString)
    {
        if (!array_key_exists($comparatorString, $this->comparatorStringMap)) {
            throw new \RuntimeException('Unknown comparator "' . $comparatorString . '" encountered"');
        }

        return $this->comparatorStringMap[$comparatorString];
    }
}
