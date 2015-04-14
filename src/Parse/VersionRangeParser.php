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


use ptlis\SemanticVersion\Label\LabelInterface;
use ptlis\SemanticVersion\Version\Comparator\ComparatorFactory;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\VersionRange\ComparatorVersion;
use ptlis\SemanticVersion\VersionRange\LogicalAnd;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Parses token streams and returns an appropriate object implementing VersionRangeInterface.
 */
class VersionRangeParser
{
    /**
     * @var ComparatorFactory
     */
    private $comparatorFactory;

    /**
     * @var LabelBuilder
     */
    private $labelBuilder;


    /**
     * Constructor.
     *
     * @param ComparatorFactory $comparatorFactory
     * @param LabelBuilder $labelBuilder
     */
    public function __construct(ComparatorFactory $comparatorFactory, LabelBuilder $labelBuilder)
    {
        $this->comparatorFactory = $comparatorFactory;
        $this->labelBuilder = $labelBuilder;
    }

    /**
     * Get a version range from caret range version tokens (e.g. ^2.2.5).
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function getFromCaretTokens(array $tokenList)
    {
        $patch = 0;
        if (5 === count($tokenList)) {
            $patch = $tokenList[4]->getValue();
        }

        return new LogicalAnd(
            new ComparatorVersion(
                $this->comparatorFactory->get('>='),
                new Version($tokenList[0]->getValue(), $tokenList[2]->getValue(), $patch)
            ),
            new ComparatorVersion(
                $this->comparatorFactory->get('<'),
                new Version($tokenList[0]->getValue() + 1)
            )
        );
    }

    /**
     * Get a version range from tilde range version tokens (e.g. ~1.5.2).
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function getFromTildeTokens(array $tokenList)
    {
        // Upto Minor version
        if (3 === count($tokenList)) {
            $range = new LogicalAnd(
                new ComparatorVersion(
                    $this->comparatorFactory->get('>='),
                    new Version($tokenList[0]->getValue(), $tokenList[2]->getValue())
                ),
                new ComparatorVersion(
                    $this->comparatorFactory->get('<'),
                    new Version($tokenList[0]->getValue() + 1)
                )
            );

        // Upto Major version
        } else {
            $range = new LogicalAnd(
                new ComparatorVersion(
                    $this->comparatorFactory->get('>='),
                    new Version($tokenList[0]->getValue(), $tokenList[2]->getValue(), $tokenList[4]->getValue())
                ),
                new ComparatorVersion(
                    $this->comparatorFactory->get('<'),
                    new Version($tokenList[0]->getValue(), $tokenList[2]->getValue() + 1)
                )
            );
        }

        return $range;
    }

    /**
     * Get a version range for a wildcard version tokens (e.g. 3.5.*).
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function getFromWildcardTokens(array $tokenList)
    {
        // With patch version always omitted this is semantically identical to a tilde range (e.g. 2.5.* is equivalent
        // to ~2.5 and ~3 is equivalent to 3.*)
        return $this->getFromTildeTokens($tokenList);
    }

    /**
     * Get a version range from version tokens including a hyphen.
     *
     * This may mean a version range, label or both.
     *
     * @param Token[] $tokenList
     *
     * @return VersionRangeInterface
     */
    public function getFromHyphenatedTokens($tokenList)
    {
        $chunkedList = $this->chunkOnHyphen($tokenList);

        switch (count($chunkedList)) {
            // Simple range or version with label
            case 2:

                // Version with label
                if (Token::LABEL_STRING === $chunkedList[1][0]->getType()) {
                    $range = new ComparatorVersion(
                        $this->comparatorFactory->get('='),
                        $this->getVersionFromTokens($chunkedList[0], $chunkedList[1])
                    );

                    // Version range
                } else {
                    $range = new LogicalAnd(
                        new ComparatorVersion(
                            $this->comparatorFactory->get('>='),
                            $this->getVersionFromTokens($chunkedList[0])
                        ),
                        $this->getUpperVersionConstraintFromTokens($chunkedList[1])
                    );
                }

                break;

            // Range where one version has label
            case 3:
                // Label belongs to left version
                if (Token::LABEL_STRING === $chunkedList[1][0]->getType()) {
                    $range = new LogicalAnd(
                        new ComparatorVersion(
                            $this->comparatorFactory->get('>='),
                            $this->getVersionFromTokens($chunkedList[0], $chunkedList[1])
                        ),
                        $this->getUpperVersionConstraintFromTokens($chunkedList[2])
                    );

                    // Label belongs to right version
                } else {
                    $range = new LogicalAnd(
                        new ComparatorVersion(
                            $this->comparatorFactory->get('>='),
                            $this->getVersionFromTokens($chunkedList[0])
                        ),
                        $this->getUpperVersionConstraintFromTokens($chunkedList[1], $chunkedList[2])
                    );
                }

                break;

            // Range where both versions have label
            case 4:
                $range = new LogicalAnd(
                    new ComparatorVersion(
                        $this->comparatorFactory->get('>='),
                        $this->getVersionFromTokens($chunkedList[0], $chunkedList[1])
                    ),
                    $this->getUpperVersionConstraintFromTokens($chunkedList[2], $chunkedList[3])
                );
                break;

            default:
                throw new \RuntimeException('Invalid version range');
                break;
        }

        return $range;
    }

    /**
     * Get a Version instance from version tokens.
     *
     * @param Token[] $versionTokenList
     * @param Token[] $labelTokenList
     *
     * @return Version
     */
    public function getVersionFromTokens(array $versionTokenList, array $labelTokenList = array())
    {
        $major = $versionTokenList[0]->getValue();
        $minor = 0;
        $patch = 0;
        $label = null;

        if (count($versionTokenList) >= 3) {
            $minor = $versionTokenList[2]->getValue();
        }

        if (count($versionTokenList) == 5) {
            $patch = $versionTokenList[4]->getValue();
        }

        if (count($labelTokenList)) {
            $label = $this->getLabelFromTokens($labelTokenList);
        }

        return new Version($major, $minor, $patch, $label);
    }

    /**
     * Split a token stream up by hyphen token, returning an array of token arrays.
     *
     * @param Token[] $tokenList
     *
     * @return Token[][]
     */
    private function chunkOnHyphen($tokenList)
    {
        $chunkedTokenList = array();

        $index = 0;
        foreach ($tokenList as $token) {
            if (Token::DASH_SEPARATOR === $token->getType()) {
                $index++;
            } else {
                $chunkedTokenList[$index][] = $token;
            }
        }

        return $chunkedTokenList;
    }

    /**
     * Determines the correct upper version constraint for a hyphenated range.
     *
     * Hyphenated ranges are implemented as described @ https://getcomposer.org/doc/01-basic-usage.md#package-versions
     *
     * @param Token[] $tokenList
     * @param Token[] $labelTokenList
     *
     * @return VersionRangeInterface
     */
    private function getUpperVersionConstraintFromTokens(array $tokenList, array $labelTokenList = array())
    {
        $minor = 0;
        $patch = 0;

        switch (count($tokenList)) {
            case 1:
                $comparator = $this->comparatorFactory->get('<');
                $major = $tokenList[0]->getValue() + 1;
                break;

            case 3:
                $comparator = $this->comparatorFactory->get('<');
                $major = $tokenList[0]->getValue();
                $minor = $tokenList[2]->getValue() + 1;
                break;

            case 5:
                $comparator = $this->comparatorFactory->get('<=');
                $major = $tokenList[0]->getValue();
                $minor = $tokenList[2]->getValue();
                $patch = $tokenList[4]->getValue();
                break;

            default:
                throw new \RuntimeException('Invalid version'); // TODO: Handle earlier in validation step
                break;
        }

        return new ComparatorVersion(
            $comparator,
            new Version(
                $major,
                $minor,
                $patch,
                $this->getLabelFromTokens($labelTokenList)
            )
        );
    }

    /**
     * Get a Label instance from label tokens.
     *
     * @param Token[] $labelTokenList
     *
     * @return LabelInterface
     */
    private function getLabelFromTokens(array $labelTokenList)
    {
        $builder = $this->labelBuilder;

        if (count($labelTokenList)) {
            $builder = $builder->setName($labelTokenList[0]->getValue());

            if (3 === count($labelTokenList)) {
                $builder = $builder->setVersion($labelTokenList[2]->getValue());
            }
        }

        return $builder->build();
    }
}
