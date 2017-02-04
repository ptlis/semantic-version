<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Test\Parse;

use PHPUnit\Framework\TestCase;
use ptlis\SemanticVersion\Comparator\GreaterOrEqualTo;

use ptlis\SemanticVersion\Comparator\GreaterThan;
use ptlis\SemanticVersion\Comparator\LessThan;
use ptlis\SemanticVersion\Parse\LogicalOperatorProcessor;
use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;
use ptlis\SemanticVersion\VersionRange\LogicalOr;

class LogicalOperatorProcessorTest extends TestCase
{
    /**
     * @covers \ptlis\SemanticVersion\Parse\LogicalOperatorProcessor
     */
    public function testSuccessLogicalAnd()
    {
        $logicalOperatorProcessor = new LogicalOperatorProcessor();

        $range = $logicalOperatorProcessor->run([
            new ComparatorVersion(new GreaterOrEqualTo(), new Version(1)),
            new Token(Token::LOGICAL_AND, '&&'),
            new ComparatorVersion(new LessThan(), new Version(2))
        ]);

        $this->assertEquals(
            new LogicalAnd(
                new ComparatorVersion(new GreaterOrEqualTo(), new Version(1)),
                new ComparatorVersion(new LessThan(), new Version(2))
            ),
            $range
        );
    }

    /**
     * @covers \ptlis\SemanticVersion\Parse\LogicalOperatorProcessor
     */
    public function testSuccessLogicalOr()
    {
        $logicalOperatorProcessor = new LogicalOperatorProcessor();

        $range = $logicalOperatorProcessor->run([
            new ComparatorVersion(new GreaterOrEqualTo(), new Version(1)),
            new Token(Token::LOGICAL_OR, '||'),
            new ComparatorVersion(new LessThan(), new Version(2))
        ]);

        $this->assertEquals(
            new LogicalOr(
                new ComparatorVersion(new GreaterOrEqualTo(), new Version(1)),
                new ComparatorVersion(new LessThan(), new Version(2))
            ),
            $range
        );
    }

    public function testSuccessOneOfTwoRanges()
    {
        $logicalOperatorProcessor = new LogicalOperatorProcessor();

        $range = $logicalOperatorProcessor->run([
            new ComparatorVersion(new GreaterThan(), new Version(1, 5)),
            new Token(Token::LOGICAL_AND, '&&'),
            new ComparatorVersion(new LessThan(), new Version(4)),

            new Token(Token::LOGICAL_OR, '|'),

            new ComparatorVersion(new GreaterOrEqualTo(), new Version(5)),
            new Token(Token::LOGICAL_AND, ''),
            new ComparatorVersion(new LessThan(), new Version(6))
        ]);


        $this->assertEquals(
            new LogicalOr(
                new LogicalAnd(
                    new ComparatorVersion(new GreaterThan(), new Version(1, 5)),
                    new ComparatorVersion(new LessThan(), new Version(4))
                ),
                new LogicalAnd(
                    new ComparatorVersion(new GreaterOrEqualTo(), new Version(5)),
                    new ComparatorVersion(new LessThan(), new Version(6))
                )
            ),
            $range
        );
    }
}