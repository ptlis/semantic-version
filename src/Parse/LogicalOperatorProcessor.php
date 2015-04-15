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

namespace ptlis\SemanticVersion\Parse;

use ptlis\SemanticVersion\VersionRange\LogicalAnd;
use ptlis\SemanticVersion\VersionRange\LogicalOr;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Build version ranges from stream of ComparatorVersions & LogicalOperators
 *
 * Basic (and limited) implementation of the shunting algorithm for logical AND / OR.
 */
class LogicalOperatorProcessor
{
    /**
     * @var array Of operator precedences & associativity.
     */
    private $operatorPrecedence = array(
        Token::LOGICAL_OR => array(
            'precedence' => 0,
            'associativity' => 'left'
        ),
        Token::LOGICAL_AND => array(
            'precedence' => 1,
            'associativity' => 'left'
        )
    );

    /**
     * Accepts an array of VersionRanges & logical operator tokens & returns a single version range implementing those
     *  constraints.
     *
     * @param array $tokenList
     *
     * @return VersionRangeInterface
     */
    public function run(array $tokenList)
    {
        return $this->buildRanges(
            $this->shuntingYard($tokenList)
        );
    }

    /**
     * Accepts an array of ComparatorVersions & logical operators in reverse polish notation & returns a single
     *  instance of a class implementing VersionRangeInterface.
     *
     * @param array $resultList
     *
     * @return VersionRangeInterface
     */
    private function buildRanges(array $resultList)
    {
        $stack = new \SplStack();

        foreach ($resultList as $result) {
            if ($result instanceof VersionRangeInterface) {
                $stack->push($result);
            } else {
                $operator1 = $stack->pop();
                $operator2 = $stack->pop();

                /** @var Token $result */
                if (Token::LOGICAL_AND === $result->getType()) {
                    $stack->push(new LogicalAnd(
                        $operator2,
                        $operator1
                    ));
                } else {
                    $stack->push(new LogicalOr(
                        $operator2,
                        $operator1
                    ));
                }
            }
        }

        return $stack->pop();
    }

    /**
     * Re-order token stream into reverse polish notation.
     *
     * @param array $tokenList
     *
     * @return array of ComparatorVersions and logical operator tokens
     */
    private function shuntingYard(array $tokenList)
    {
        $operatorStack = new \SplStack();
        $output = new \SplQueue();

        foreach ($tokenList as $token) {

            // Accumulate Versions & Comparators
            if ($token instanceof VersionRangeInterface) {
                $output->enqueue($token);

            // Handle operators
            } elseif ($token instanceof Token) {

                // Loop while the current token has higher precedence then the stack token
                $operator1 = $token;
                while (
                    $this->hasOperator($operatorStack)
                    && ($operator2 = $operatorStack->top())
                    && $this->hasLowerPrecedence($operator1, $operator2)
                ) {
                    $output->enqueue($operatorStack->pop());
                }

                $operatorStack->push($operator1);

            } else {
                throw new \RuntimeException('Invalid version number');
            }
        }

        // Merge remaining operators onto output list
        while ($this->hasOperator($operatorStack)) {
            $output->enqueue($operatorStack->pop());
        }

        return iterator_to_array($output);
    }

    /**
     * Returns true if we have an operator on the stack.
     *
     * @param \SplStack $stack
     * @return bool
     */
    private function hasOperator(\SplStack $stack)
    {
        return count($stack) > 0;
    }

    /**
     * Returns true if the first operator has lower precedence than the second.
     *
     * @param Token $operator1
     * @param Token $operator2
     *
     * @return bool
     */
    private function hasLowerPrecedence(Token $operator1, Token $operator2)
    {
        $associativity1 = $this->operatorPrecedence[$operator1->getType()]['associativity'];

        $precedence1 = $this->operatorPrecedence[$operator1->getType()]['precedence'];
        $precedence2 = $this->operatorPrecedence[$operator2->getType()]['precedence'];

        return ('left' === $associativity1 && $precedence1 === $precedence2)
            || $precedence1 < $precedence2;
    }
}
