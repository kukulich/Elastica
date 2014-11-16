<?php

namespace Elastica\QueryBuilder;

/**
 * DSL Interface
 *
 * @package Elastica
 * @author Manuel Andreo Garcia <andreo.garcia@googlemail.com>
 */
interface DSL
{
    const TYPE_QUERY = 'query';
    const TYPE_FILTER = 'filter';
    const TYPE_AGGREGATION = 'agg';
    const TYPE_SUGGEST = 'suggest';

    /**
     * must return type for QueryBuilder usage
     *
     * @return string
     */
    public function getType();
}
