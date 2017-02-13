<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Version\Label;

use ptlis\SemanticVersion\Parse\Token;

/**
 * Immutable builder for label instances.
 */
final class LabelBuilder
{
    /** @var string */
    private $name;

    /** @var int|null */
    private $version;

    /** @var array Map of string encoded labels to label precedences */
    private $labelPrecedenceMap = [
        'alpha' => Label::PRECEDENCE_ALPHA,
        'beta' => Label::PRECEDENCE_BETA,
        'rc' => Label::PRECEDENCE_RC
    ];


    /**
     * Constructor.
     *
     * @param string $name
     * @param int|null $version
     */
    public function __construct($name = '', $version = null)
    {
        $this->name = $name;
        $this->version = $version;
    }

    /**
     * Set label name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        return new LabelBuilder(
            $name,
            $this->version
        );
    }

    /**
     * Set label version.
     *
     * @param int|null $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        return new LabelBuilder(
            $this->name,
            $version
        );
    }

    /**
     * Build a label from the provided specification
     *
     * @return LabelInterface
     */
    public function build()
    {
        // Default to miscellaneous 'dev' label
        $label = new Label(Label::PRECEDENCE_DEV, $this->version, $this->name);

        // No Label present or a dev label (these are a special-case for packagist branch versions - a version like
        // 1.0.x-dev is equivalent to 1.0.* in  conventional notation - there are additional semantics attached to this
        // but they're not important for our purposes
        if (!strlen($this->name) || 'dev' === $this->name) {
            $label = new Label(Label::PRECEDENCE_ABSENT);

        // Alpha, Beta & RC standard labels
        } elseif (array_key_exists($this->name, $this->labelPrecedenceMap)) {
            $label = new Label($this->labelPrecedenceMap[$this->name], $this->version);
        }

        return $label;
    }

    /**
     * Build a label from a token list.
     *
     * @param Token[] $labelTokenList
     *
     * @return LabelInterface|null
     */
    public function buildFromTokens(array $labelTokenList)
    {
        $label = $this->build();

        switch (count($labelTokenList)) {

            // No label
            case 0:
                // Do Nothing
                break;

            // Version string part only
            case 1:
                $label = $this
                    ->setName($labelTokenList[0]->getValue())
                    ->build();
                break;

            // Label version
            case 3:
                $label = $this
                    ->setName($labelTokenList[0]->getValue())
                    ->setVersion($labelTokenList[2]->getValue())
                    ->build();
                break;

            default:
                throw new \RuntimeException('Invalid version');
        }

        return $label;
    }
}
