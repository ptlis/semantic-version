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

namespace ptlis\SemanticVersion\Parse;

/**
 * A token from a version number.
 */
class Token
{
    /**
     * '0-9' Numeric component of a version number (may be part of the label e.g. the final digit of 1.2.7-alpha.2).
     */
    const DIGITS = 'digits';

    /**
     * '*' Wildcard numeric component of a version number (1.2.*).
     */
    const WILDCARD_DIGITS = 'wildcard-digits';

    /**
     * '-' Contextually either a version range or label separator.
     */
    const DASH_SEPARATOR = 'dash-separator';

    /**
     * '.' Separator between version components.
     */
    const DOT_SEPARATOR = 'dot-separator';

    /**
     * '~' Specifies a strict range match (patch version increments only) against the subsequent version number.
     *
     * E.g. the version number ~1.7.3 is equivalent to >=1.7.3 <1.8.0
     */
    const TILDE_RANGE = 'strict-range';

    /**
     * '^' Specifies loose range match (minor & patch version increments) against the subsequent version number.
     *
     * E.g. the version number ^1.7.3 is equivalent to >=1.7.3 <2.0.0
     */
    const CARET_RANGE = 'loose-range';

    /**
     * 'a-zA-Z0-9' The string component of a label.
     */
    const LABEL_STRING = 'label-string';

    /**
     * Comparator for matching version number.
     */
    const EQUAL_TO = 'comparator-equal-to';

    /**
     * Greater than comparator.
     */
    const GREATER_THAN = 'comparator-greater-than';

    /**
     * Greater than or equal to comparator.
     */
    const GREATER_THAN_EQUAL = 'comparator-greater-than-equal-to';

    /**
     * Less than comparator.
     */
    const LESS_THAN = 'comparator-less-than';

    /**
     * Less than or equal to comparator.
     */
    const LESS_THAN_EQUAL = 'comparator-less-than-equal-to';

    /**
     * Logical and between two constraints.
     */
    const LOGICAL_AND = 'and';

    /**
     * Logical or between to constraints.
     */
    const LOGICAL_OR = 'or';


    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $value;


    /**
     * Constructor.
     *
     * @param string $type One of class constants.
     * @param string $value
     */
    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * Get the token type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the token value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
