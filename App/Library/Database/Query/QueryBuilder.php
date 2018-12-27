<?php

namespace Database\Query;

use Database\Connection;
use Pagination\Paginator;
use Support\Objects\ObjectContainer;
use Closure;
use App\Core\Model;

/**
 *Provides a set of methods for querying objects that implement Model and Connection
 */
trait QueryBuilder
{
    /**
     * Summary of $connection
     * @var Connection
     */
    protected $connection;

    /**
     * Summary of $actionCallBack
     * @var Closure|null
     */
    private $actionCallBack;

    /**
     * Summary of $entities
     * @var Model[]
     */
    private $entities;

    private $params = [];

    /**
     * Get all of the models from the database.
     *
     * @param  array|mixed  $columns
     * @return ObjectContainer[]
     */
    public function All()
    {
        return $this->connection->select($this->table, $this->GetAttributes());
    }

    /**
     * @param $columns
     * @param $values
     * @return mixed
     */
    public function Any($columns, ...$values): Paginator
    {
        $wheres = ['OR' => []];

        foreach ($columns as $key => $column)
        {
            $wheres['OR'][$column . '[~]'] = $values[$key];
        }

        return $this->Where('*', $wheres);
    }

    /**
     * Summary of Find
     * @param mixed $key
     * @return \array|Support\Objects\ObjectContainer?
     */
    public function Find($key)
    {
        return $this->connection->single($this->table, '*', [$this->primaryKey => $key]);
    }

    /**
     * @param Connection $connection
     * @param string $table
     * @param $columnName
     */
    public static function HasColumn(Connection $connection, string $table, $columnName)
    {
        $columns = $connection->query("SHOW COLUMNS FROM {$table}", false)->fetchAll();

        foreach ($columns as $column)
        {
            if (strtolower($column['Field']) === strtolower($columnName))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Summary of Matches
     * @param string[] $columns
     * @param array $values
     * @return Paginator
     */
    public function Matches($columns, ...$values): Paginator
    {
        $wheres = [];

        foreach ($columns as $index => $column)
        {
            $wheres[$column] = $values[$index];
        }

        return $this->Where('*', $wheres);
    }

    /**
     * @param $columns
     * @param array $attributes
     * @return mixed
     */
    public function OrderBy($columns = '*', array $attributes = [])
    {

        $wheres['ORDER'] = $attributes;

        foreach ($attributes as $key => $value)
        {
            if (!$this->CheckColumnExists($key))
            {
                unset($wheres['ORDER'][$key]);
            }
        }

        if (empty($wheres['ORDER']))
        {
            unset($wheres['ORDER']);
        }

        return $this->Where('*', $wheres);
    }

    /**
     * Filters a sequence of values based on a predicate.
     *
     * @param array $where A function to test each element for a condition
     * @param array $join Join tables
     * @return \Traversable
     */
    public function Query(array $where): iterable
    {
        return $this->connection->select($this->table, $this->GetAttributes(), $where);
    }

    /**
     * Projects each element of a sequence into a new form by incorporating the element's index
     *
     * @param array|string $columns A transform function to apply to each source element
     * @return Paginator
     */
    public function Select($columns = '*'): Paginator
    {
        return new Paginator($this->connection, new QueryExpression($this->table, $columns, ['LIMIT' => []]), $this->perPage, 1);
    }

    /**
     * Returns the only element of a sequence, or a default value if the sequence is empty;
     *
     * @param array|string $colums
     * @param array $wheres
     * @return ObjectContainer
     */
    public function Single($colums = '*', array $wheres)
    {
        return $this->connection->single($this->table, $colums, $wheres);
    }

    /**
     * Summary of Where
     * @param array|string $columns
     * @param array $wheres
     * @return Paginator
     */
    public function Where($columns = '*', array $wheres): Paginator
    {
        return new Paginator($this->connection, new QueryExpression($this->table, $columns, array_merge($wheres, ['LIMIT' => []])), $this->perPage, 1);
    }

    /**
     * Summary of RawWhere
     * @param mixed $columns
     * @param array $wheres
     * @return ObjectContainer[]
     */
    public function RawWhere($columns = '*', array $wheres)
    {
        return iterator_to_array($this->connection->select($this->table,$columns,$wheres));
    }

    /**
     * @param string $columName
     */
    protected function CheckColumnExists(string $columName)
    {
        $columns = $this->connection->query("SHOW COLUMNS FROM {$this->table}", false)->fetchAll();

        foreach ($columns as $column)
        {
            if (strtolower($column['Field']) === strtolower($columName))
            {
                return true;
            }
        }

        return false;
    }

    public function Transaction($extraParams=[],Closure $action,Model&...$entities)
    {
        $this->actionCallBack = $action;

        $this->entities = $entities;

        $this->params = $extraParams;

        return $this->Action();
    }

    private function Action()
    {
        return $this->connection->action(
            function($connection)
            {
                $reponse = $this->actionCallBack->call($this,$this->params,$this,...$this->entities);

                $this->entities = [];

                $this->actionCallBack = null;

                $this->params = [];

                return $reponse;
            });
    }
}
