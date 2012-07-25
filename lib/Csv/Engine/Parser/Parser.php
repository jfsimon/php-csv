<?php

namespace Csv\Engine\Parser;

use Csv\Engine\ParserInterface;
use Csv\Engine\Tokenizer\TokenIterator;
use Csv\Engine\Tokenizer\Token;
use Csv\Engine\Enclosure;
use Csv\Exception\ParsingFinishedException;

/**
 * Parser.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Parser implements ParserInterface
{
    /**
     * @var TokenIterator
     */
    private $tokens;

    /**
     * Constructor.
     *
     * @param TokenIterator $tokens
     */
    public function __construct(TokenIterator $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(State $state)
    {
        /** @var Token $token */
        foreach ($this->tokens as $token) {
            switch (true) {
                // triple enclosure cases
                case $token->is(Token::ENCLOSURE_TRIPLE_START):
                case $token->is(Token::ENCLOSURE_TRIPLE_BOUNDARY):
                    $state->addContent($this->getEnclosure()->start);
                    $state->setEnclosed(!$state->isEnclosed());
                    break;
                case $token->is(Token::ENCLOSURE_TRIPLE_END):
                    $state->addContent($this->getEnclosure()->end);
                    $state->setEnclosed(!$state->isEnclosed());
                    break;

                // escaped enclosure cases
                case $token->is(Token::ENCLOSURE_ESCAPED_BOUNDARY):
                case $token->is(Token::ENCLOSURE_ESCAPED_START):
                case $token->is(Token::ENCLOSURE_ESCAPED_END):
                    $state->addContent($this->getEnclosure()->disclose($token->getContent()));
                    break;

                // enclosed cases
                case $state->isEnclosed() && $token->is(Token::ENCLOSURE_END):
                    $state->setEnclosed(false);
                    break;
                case $state->isEnclosed():
                    $state->addContent($token->getContent());
                    break;

                // disclosed cases
                case $token->is(Token::ENCLOSURE_START):
                    $state->setEnclosed(true);
                    break;
                case $token->is(Token::SEPARATOR):
                    $state->nextCell();
                    break;
                case $token->is(Token::LINE_BREAK):
                    return $state->fetchRow();

                // default case
                default:
                    $state->addContent($token->getContent());
            }
        }

        return $state->fetchRow();
    }

    /**
     * @return Enclosure
     *
     * @throws \LogicException
     */
    private function getEnclosure()
    {
        $enclosure = $this->tokens->getEnclosure();

        if (null === $enclosure) {
            throw new \LogicException('Enclosure is not set and should not be required..');
        }

        return $enclosure;
    }
}
