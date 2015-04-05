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
 * Tokenizer for version numbers.
 */
class VersionTokenizer
{
    /**
     * Accepts a version string & returns an array of tokenized values.
     *
     * @param string $versionString
     *
     * @return string[]
     */
    function tokenize($versionString)
    {
        $tokenList = array();
        $digitAccumulator = '';
        $stringAccumulator = '';

        // Iterate through string, character-by-character
        for ($i = 0; $i < strlen($versionString); $i++) {
            $chr = $this->getCharacter($i, $versionString);

            switch (true) {

                // Simple token types - separators and range indicators
                case !is_null($this->getSimpleToken($chr)):

                    // Handle preceding digits or labels
                    $this->conditionallyAddToken(Token::DIGITS, $digitAccumulator, $tokenList);
                    $this->conditionallyAddToken(Token::LABEL_STRING, $stringAccumulator, $tokenList);

                    $tokenList[] = $this->getSimpleToken($chr);
                    break;

                // Comparator token types
                case !is_null($this->getComparatorToken($i, $versionString)):

                    // Handle preceding digits or labels
                    $this->conditionallyAddToken(Token::DIGITS, $digitAccumulator, $tokenList);
                    $this->conditionallyAddToken(Token::LABEL_STRING, $stringAccumulator, $tokenList);

                    $token = $this->getComparatorToken($i, $versionString);
                    $tokenList[] = $token;

                    if (strlen($token->getValue()) > 1) {
                        $i++;
                    }

                    break;

                // Skip the 'v' character if immediately preceding a digit
                case $this->isPrefixedV($i, $versionString):
                    // Do nothing
                    // TODO: Should we store this for correct reverse transformation?
                    break;

                // Start accumulating on the first non-digit & continue until we reach a separator
                case !is_numeric($chr) || strlen($stringAccumulator):
                    $stringAccumulator .= $chr;
                    break;

                // Simple digits
                default:
                    $digitAccumulator .= $chr;
                    break;
            }
        }

        // Handle any remaining digits or labels
        $this->conditionallyAddToken(Token::DIGITS, $digitAccumulator, $tokenList);
        $this->conditionallyAddToken(Token::LABEL_STRING, $stringAccumulator, $tokenList);

        return $tokenList;
    }

    /**
     * Tries to find a comparator token beginning at the specified index.
     *
     * @param int $index
     * @param string $versionString
     *
     * @return Token|null
     */
    private function getComparatorToken($index, $versionString)
    {
        $comparatorMap = array(
            '>' => Token::GREATER_THAN,
            '>=' => Token::GREATER_THAN_EQUAL,
            '<' => Token::LESS_THAN,
            '<=' => Token::LESS_THAN_EQUAL,
            '=' => Token::EQUAL_TO
        );

        $chr = substr($versionString, $index, 1);

        $comparator = '';
        if (array_key_exists($chr, $comparatorMap)) {
            $nextChr = $this->getCharacter($index + 1, $versionString);

            $comparator = $chr;
            // We have a '<' or '>' and the next token is '=' - create a compound comparator
            if (('<' === $chr || '>' === $chr) && '=' === $nextChr) {
                $comparator .= $nextChr;
            }
        }

        $token = null;
        if (array_key_exists($comparator, $comparatorMap)) {
            $token = new Token($comparatorMap[$comparator], $comparator);
        }

        return $token;
    }

    /**
     * Get a token from a single-character.
     *
     * @param string $chr
     *
     * @return Token|null
     */
    private function getSimpleToken($chr)
    {
        $tokenMap = array(
            '-' => Token::DASH_SEPARATOR,
            '.' => Token::DOT_SEPARATOR,
            '~' => Token::TILDE_RANGE,
            '^' => Token::CARET_RANGE,
            '*' => Token::WILDCARD_DIGITS
        );

        $token = null;
        if (array_key_exists($chr, $tokenMap)) {
            $token = new Token($tokenMap[$chr], $chr);
        }

        return $token;
    }

    /**
     * Add a token to the list if the string length is greater than 0, empties string.
     *
     * @param string $type One of Token class constants
     * @param string $value
     * @param Token[] $tokenList
     */
    public function conditionallyAddToken($type, &$value, &$tokenList)
    {
        if (strlen($value)) {
            $tokenList[] = new Token(
                $type,
                $value
            );

            $value = '';
        }
    }

    /**
     * Returns true if the character is a 'v' prefix to a version number (e.g. v1.0.7).
     *
     * @param int $index
     * @param string $versionString
     *
     * @return bool
     */
    private function isPrefixedV($index, $versionString)
    {
        $chr = $this->getCharacter($index, $versionString);

        return 'v' === $chr
            && $this->hasAnotherCharacter($index, $versionString)
            && is_numeric($this->getCharacter($index + 1, $versionString));
    }

    /**
     * Returns true if there are more characters to read after $index.
     *
     * @param int $index
     * @param string $versionString
     *
     * @return bool
     */
    private function hasAnotherCharacter($index, $versionString)
    {
        return strlen($this->getCharacter($index + 1, $versionString));
    }

    /**
     * Get the next character after the specified index or an empty string if not present.
     *
     * @param int $index
     * @param string $versionString
     * @return string
     */
    private function getCharacter($index, $versionString)
    {
        $chr = '';
        if ($index < strlen($versionString)) {
            $chr = substr($versionString, $index, 1);
        }

        return $chr;
    }
}