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

namespace ptlis\SemanticVersion;

use ptlis\SemanticVersion\Parse\VersionParser;
use ptlis\SemanticVersion\Parse\VersionTokenizer;
use ptlis\SemanticVersion\Version\Label\LabelBuilder;
use ptlis\SemanticVersion\VersionRange\VersionRangeInterface;

/**
 * Simple class to provide version parsing with good defaults.
 */
class VersionEngine
{
    /**
     * @var VersionTokenizer
     */
    private $tokenizer;

    /**
     * @var VersionParser
     */
    private $parser;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tokenizer = new VersionTokenizer();
        $this->parser = new VersionParser(
            new LabelBuilder()
        );
    }

    public function parse()
    {
        // TODO: Re-implement
    }

    /**
     * Parse a version range & return a range object encoding those rules.
     *
     * @param string $rangeString
     *
     * @return VersionRangeInterface
     */
    public function parseRange($rangeString)
    {
        $tokenList = $this->tokenizer->tokenize($rangeString);

        return $this->parser->parseRange($tokenList);
    }
}
