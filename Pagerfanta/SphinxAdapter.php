<?php

namespace Fullpipe\SphinxSearchBundle\Pagerfanta;

use Pagerfanta\Adapter\AdapterInterface;
use Fullpipe\SphinxSearchBundle\Services\Search\SphinxSearch;

class SphinxAdapter implements AdapterInterface
{
    private $sphinxClient;
    private $query;
    private $indexes;
    private $options;
    private $escapeQuery;

    public function __construct(SphinxSearch $sphinxClient)
    {
        $this->sphinxClient = $sphinxClient;
    }

    public function setSearchParams($query, array $indexes, array $options = array(), $escapeQuery = true)
    {
        $this->query = $query;
        $this->indexes = $indexes;
        $this->options = $options;
        $this->escapeQuery = $escapeQuery;
    }

    public function getNbResults()
    {
        $options = array_merge($this->options, array(
            'result_offset' => 0,
            'result_limit' => 1,
        ));

        $result = $this->sphinxClient->search(
            $this->query,
            $this->indexes,
            $options,
            $this->escapeQuery
        );

        return empty($result) ? 0 : (int) $result['total'];
    }

    public function getSlice($offset, $length)
    {
        $options = array_merge($this->options, array(
            'result_offset' => $offset,
            'result_limit' => $length,
        ));

        $result = $this->sphinxClient->search(
            $this->query,
            $this->indexes,
            $options,
            $this->escapeQuery
        );

        return !empty($result['matches']) ? $result['matches'] : array() ;
    }
}
