<?php

/**
 * Tests to ensure correct behaviour of VersionFactory.
 *
 * PHP Version 5.3
 *
 * Based off the tests for vierbergenlars\SemVar https://github.com/vierbergenlars/php-semver/
 *
 * @copyright   (c) 2014 Brian Ridley
 * @author      Brian Ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\Version;

use ptlis\SemanticVersion\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\ComparatorVersion\ComparatorVersionFactory;
use ptlis\SemanticVersion\Label\LabelFactory;
use ptlis\SemanticVersion\Version\VersionFactory;
use ptlis\SemanticVersion\VersionRegex;

/**
 * Tests to ensure correct behaviour of VersionFactory.
 */
class ComparatorVersionFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testValidFactoryGet()
    {
        $regexProvider = new VersionRegex();
        $comparatorFac = new ComparatorFactory();
        $versionFac = new VersionFactory($regexProvider, new LabelFactory());
        $comVerFac = new ComparatorVersionFactory($regexProvider, $versionFac, $comparatorFac);

        $comparatorVersion = $comVerFac->get(
            $comparatorFac->get('<='),
            $versionFac->get(1, 5, 0)
        );

        $this->assertEquals('<=1.5.0', $comparatorVersion->__toString());
    }


    public function testInvalidFactoryGetOne()
    {
        $this->setExpectedException(
            'ptlis\SemanticVersion\Exception\InvalidVersionException',
            'Failed to set major version to invalid value "bob"'
        );

        $versionFac = new VersionFactory(new VersionRegex(), new LabelFactory());

        $versionFac->get('bob', 5, 0);
    }


    public function testInvalidFactoryGetTwo()
    {
        $this->setExpectedException(
            'ptlis\SemanticVersion\Exception\InvalidVersionException',
            'The provided options are invalid.'
        );

        $versionFac = new VersionFactory(new VersionRegex(), new LabelFactory());

        $versionFac->get(null, 5, 0);
    }
}
