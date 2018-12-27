<?php

namespace Database\Query;

use Closure;
use Database\Connection;

/**
 * Summary of QueryExpression
 */
final class QueryExpression
{
    /**
     * Summary of $columns
     * @var array
     */
    public $columns;

    /**
     * Summary of $connection
     * @var Connection
     */
    public $connection;

    /**
     * Summary of $data
     * @var array
     */
    public $data;

    /**
     * Summary of $table
     * @var string
     */
    public $table;

    /**
     * Summary of $expression
     * @var callable|Closure
     */
    private $expression;

    /**
     * Summary of Selector
     * @return callable|Closure
     */
    public function Execute()
    {
        $this->expression =
        function ()
        {

            $result = $this->connection->select($this->table, $this->columns, $this->data);

            return iterator_to_array($result);
        };

        return $this->expression;
    }

    /**
     * Summary of Selector Raw
     * @return callable|Closure
     */
    public function ExecuteRaw()
    {
        $this->expression =
        function (string $query)
        {

            $result = $this->connection->query($query, true, $this->data);

            return iterator_to_array($result);
        };

        return $this->expression;
    }

    /**
     * @param Connection $connection
     */
    public function SetConnection(Connection &$connection)
    {
        $this->connection = &$connection;
    }

    /**
     * Summary of __construct
     * @param string $table
     * @param array|string $columns
     * @param array $expressions
     */
    public function __construct(string $table, $columns, array $expressions = [])
    {
        $this->data = $expressions;
        $this->table = $table;
        $this->columns = $columns;
    }
}
