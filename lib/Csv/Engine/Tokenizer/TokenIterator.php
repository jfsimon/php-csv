<?php

namespace Csv\Engine\Tokenizer;

use Csv\Engine\WorkerIterator;
use Csv\Resource\ReaderInterface;
use Csv\Exception\ReadingFinishedException;

/**
 * Token iterator.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class TokenIterator extends WorkerIterator
{
    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var ReaderInterface
     */
    private $reader;

    /**
     * @var array
     */
    private $tokens;

    /**
     * Constructor.
     *
     * @param Tokenizer       $tokenizer
     * @param ReaderInterface $reader
     */
    public function __construct(Tokenizer $tokenizer, ReaderInterface $reader)
    {
        $this->tokenizer = $tokenizer;
        $this->reader    = $reader;
        $this->tokens    = array();
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->tokens[0];
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        array_shift($this->tokens);
        parent::next();
    }

    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        if (0 === count($this->tokens)) {
            try {
                $this->tokens = $this->tokenizer->tokenize($this->reader->readLine());
            } catch (ReadingFinishedException $e) {
                $this->finish();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function reiterate()
    {
        $this->next();
    }
}
