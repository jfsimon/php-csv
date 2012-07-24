<?php

namespace Csv\Engine\Parser;

use Csv\Engine\WorkerIterator;
use Csv\Exception\ParsingFinishedException;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class RowIterator extends WorkerIterator
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var array
     */
    private $row;

    /**
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
        $this->row    = array();
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->row;
    }

    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        try {
            $this->row = $this->parser->parse(new State());
        } catch (ParsingFinishedException $e) {
            $this->finish();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function reiterate()
    {
        throw new \RuntimeException('Reset not permitted.');
    }
}
