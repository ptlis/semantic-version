<?php

/**
 * @copyright   (c) 2014-2017 brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ptlis\SemanticVersion\Parse;

use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\Version\Version;
use ptlis\SemanticVersion\Version\VersionInterface;

/**
 * Matcher that can be used to parse version numbers.
 */
final class VersionParser
{
    /** @var LabelBuilder */
    private $labelBuilder;

    /** Array of token patterns that can be used to construct a valid semantic version number */
    private $validPatterns = [
        [Token::DIGITS],
        [Token::DIGITS, Token::DOT_SEPARATOR], // Allow trailing dot
        [Token::DIGITS, Token::DOT_SEPARATOR, Token::DIGITS],
        [Token::DIGITS, Token::DOT_SEPARATOR, Token::DIGITS, Token::DOT_SEPARATOR], // Allow trailing dot
        [Token::DIGITS, Token::DOT_SEPARATOR, Token::DIGITS, Token::DOT_SEPARATOR, Token::DIGITS],
        [Token::DIGITS, Token::DOT_SEPARATOR, Token::DIGITS, Token::DOT_SEPARATOR, Token::DIGITS,
            Token::DASH_SEPARATOR, Token::LABEL_STRING],
        [Token::DIGITS, Token::DOT_SEPARATOR, Token::DIGITS, Token::DOT_SEPARATOR, Token::DIGITS,
            Token::DASH_SEPARATOR, Token::LABEL_STRING, Token::DOT_SEPARATOR, Token::DIGITS]
    ];


    /**
     * @param LabelBuilder $labelBuilder
     */
    public function __construct(
        LabelBuilder $labelBuilder
    ) {
        $this->labelBuilder = $labelBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function canParse(array $tokenList)
    {
        $isVersion = false;
        foreach ($this->validPatterns as $pattern) {
            $isVersion = $isVersion || $this->tokensMatchPattern($tokenList, $pattern);
        }

        return $isVersion;
    }


    /**
     * Parses the token list into an appropriate value type.
     *
     * @throws \RuntimeException If the version cannot be parsed.
     *
     * @param Token[] $tokenList
     *
     * @return VersionInterface
     */
    public function parse(array $tokenList)
    {
        if (!$this->canParse($tokenList)) {
            throw new \RuntimeException('Invalid version');
        }

        $major = $tokenList[0]->getValue();
        $minor = 0;
        $patch = 0;
        $label = null;

        if (count($tokenList) >= 3) {
            $minor = $tokenList[2]->getValue();
        }

        if (count($tokenList) >= 5) {
            $patch = $tokenList[4]->getValue();
        }

        // Build label from tokens following hyphen seperator
        if (count($tokenList) >= 7) {
            $label = $this->labelBuilder->buildFromTokens(array_slice($tokenList, 6));
        }

        return new Version($major, $minor, $patch, $label);
    }

    /**
     * Returns true if the tokens match the specified pattern.
     *
     * @param Token[] $tokenList
     * @param string[] $pattern
     *
     * @return bool
     */
    private function tokensMatchPattern(
        array $tokenList,
        array $pattern
    ) {
        $matches = count($tokenList) === count($pattern);

        foreach ($tokenList as $index => $token) {
            if ($matches) {
                $matches = $pattern[$index] === $token->getType();
            }
        }

        return $matches;
    }
}