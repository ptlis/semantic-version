<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Comparator;

use PHPUnit\Framework\TestCase;
use ptlis\SemanticVersion\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Comparator\GreaterThan;

final class ComparatorFactoryTest extends TestCase
{
    /**
     * @covers \ptlis\SemanticVersion\Comparator\ComparatorFactory
     */
    public function testValidComparator()
    {
        $comparator = (new ComparatorFactory())->get('>');

        $this->assertEquals(new GreaterThan(), $comparator);
    }

    /**
     * @covers \ptlis\SemanticVersion\Comparator\ComparatorFactory
     */
    public function testInvalidComparator()
    {
        $this->expectException('\RuntimeException');
        $this->expectExceptionMessage('Unknown comparator "??" encountered"');

        (new ComparatorFactory())
            ->get('??');
    }
}