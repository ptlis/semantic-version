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

namespace ptlis\SemanticVersion\Comparator;

use ptlis\SemanticVersion\Parse\Token;

/**
 * Factory to build comparator instances from string representation.
 */
class ComparatorFactory
{
    /**
     * @var ComparatorInterface[]
     */
    private $comparatorTokenMap;

    /**
     * @var ComparatorInterface[]
     */
    private $comparatorStringMap;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->comparatorTokenMap = array(
            Token::GREATER_THAN => new GreaterThan(),
            Token::GREATER_THAN_EQUAL => new GreaterOrEqualTo(),
            Token::LESS_THAN => new LessThan(),
            Token::LESS_THAN_EQUAL => new LessOrEqualTo(),
            Token::EQUAL_TO => new EqualTo()
        );

        $this->comparatorStringMap = array(
            '>' => new GreaterThan(),
            '>=' => new GreaterOrEqualTo(),
            '<' => new LessThan(),
            '<=' => new LessOrEqualTo(),
            '=' => new EqualTo()
        );
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
        $comparator = null;
        if (array_key_exists($comparatorString, $this->comparatorStringMap)) {
            $comparator = $this->comparatorStringMap[$comparatorString];
        }

        return $comparator;
    }
}
