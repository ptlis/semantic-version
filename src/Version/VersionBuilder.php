<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Version;

use ptlis\SemanticVersion\Parse\Token;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;

/**
 * Immutable builder for version instances.
 */
final class VersionBuilder
{
    /** @var LabelBuilder */
    private $labelBuilder;


    /**
     * Constructor.
     *
     * @param LabelBuilder $labelBuilder
     */
    public function __construct(LabelBuilder $labelBuilder)
    {
        $this->labelBuilder = $labelBuilder;
    }

    /**
     * Build a label from a token list.
     *
     * @param Token[] $versionTokenList
     * @param Token[] $labelTokenList
     *
     * @return VersionInterface|null
     */
    public function buildFromTokens(array $versionTokenList, array $labelTokenList)
    {
        $minor = 0;
        $patch = 0;
        $label = null;

        switch (count($versionTokenList)) {

            // Major Only
            case 1:
                $major = $versionTokenList[0]->getValue();
                break;

            // Major, minor
            case 3:
                $major = $versionTokenList[0]->getValue();
                $minor = $versionTokenList[2]->getValue();
                break;

            // Major, minor, patch
            case 5:
                $major = $versionTokenList[0]->getValue();
                $minor = $versionTokenList[2]->getValue();
                $patch = $versionTokenList[4]->getValue();
                break;

            default:
                throw new \RuntimeException('Invalid version');
        }

        return new Version(
            $major,
            $minor,
            $patch,
            $this->labelBuilder->buildFromTokens($labelTokenList)
        );
    }
}
