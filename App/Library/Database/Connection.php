<?php
namespace Database;

use PDO;
use Medoo\Medoo;
use PDOStatement;
use App\Config\Config;
use Support\Objects\ObjectContainer;

/**
 * Summary of Connection
 */
class Connection extends Medoo
{
    /**
     * Summary of $connection
     * @var Connection|null
     */
    private static $connection;

    /**
     * Summary of GetConnection
     * @return Connection|null
     */
    public static function GetConnection(): Connection
    {
        if (static::$connection === null)
        {
            static::Initialize();

            return static::$connection;
        }

        return static::$connection;
    }

    /**
     * Summary of __construct
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Counts the number of row
     * @param string $table The table name.
     * @param array $join Table relativity for table joining.
     * @param string $column The target column will be counted.
     * @param array $where The WHERE clause to filter records.
     * @return int The number of rows
     */
    public function count($table, $join = null, $column = null, $where = null): int
    {
        return parent::count($table, $join, $column, $where);
    }

    /**
     * Output the generated SQL without execute it.
     *
     */
    public function debug()
    {
        return;
    }

    /**
     * Delete data from table
     * @param string $table The table name.
     * @param array $where The WHERE clause to filter records.
     * @return int
     */
    public function delete($table, $where): int
    {
        return parent::delete($table, $where)->rowCount();
    }

    /**
     * Get only one record from table
     * @param string $table The table name.
     * @param array $join Table relativity for table joining.
     * @param string|array $columns The target columns of data will be fetch.
     * @param array $where The WHERE clause to filter records.
     * @return ObjectContainer
     */
    public function get($table, $join = null, $columns = null, $where = null)
    {
        $results = parent::get($table, $join, $columns, $where);

        if (!empty($results))
        {
            return ObjectContainer::BuilObject(array_keys($results), ...array_values($results));
        }

        return ObjectContainer::EmptyObject();
    }

    /**
     * Determine whether the target data existed
     * @param string $table The table name.
     * @param array $join Table relativity for table joining.
     * @param array $where The WHERE clause to filter records.
     * @return boolean
     */
    public function has($table, $join, $where = null): bool
    {
        return parent::has($table, $join, $where);
    }

    /**
     * Insert new records in table
     * @param string $table The table name.
     * @param array $datas The data that will be inserted into table.
     * @return int
     */
    public function insert($table, $datas)
    {
        return parent::insert($table, $datas)->rowCount();
    }

    /**
     * Get the maximum value for the column
     * @param string $table The table name.
     * @param array $join Table relativity for table joining.
     * @param string|array $columns The target columns of data will be fetch.
     * @param array $where The WHERE clause to filter records.
     * @return mixed
     */
    public function max($table, $join, $column = null, $where = null)
    {
        return parent::max($table, $join, $column, $where);
    }

    /**
     * Execute customized raw query
     * @param string $query The SQL query.
     * @param bool $asObject Transform all results to ObjectContainer Collection
     * @param array $map The array of input parameters value for prepared statement
     * @return mixed|ObjectContainer[]|PDOStatement
     */
    public function query($query, $asObject = true, $map = array())
    {
        if (!$asObject)
        {
            return parent::query($query, $map);
        }

        $results = parent::query($query, $map)->fetchAll();

        $objects = [];

        foreach ($results as $object)
        {
            $objects[] = ObjectContainer::BuilObject(array_keys($object), ...array_values($object));
        }

        return $objects;
    }

    /**
     * Replace old data into new one
     * @param string $table The table name.
     * @param string $columns The target columns of data will be replaced.
     * @param mixed $where The WHERE clause to filter records.
     * @return int
     */
    public function replace($table, $columns, $where = null): int
    {
        return parent::replace($table, $columns, $where)->rowCount();
    }

    /**
     * Select data from database
     * @param string $table The table name.
     * @param string|array $join Table relativity for table joining. Ignore it if no table joining required.
     * @param string|array $columns The target columns of data will be fetched.
     * @param array $where The WHERE clause to filter records.
     * @return \Traversable
     */
    public function select($table, $join, $columns = null, $where = null): iterable
    {
        if (empty($join))
        {
            $join = '*';
        }

        $results = parent::select($table, $join, $columns, $where);

        foreach ($results as $object)
        {
            yield ObjectContainer::BuilObject(array_keys($object), ...array_values($object));
        }
    }

    /**
     * Get only one record from table
     * @param string $table The table name.
     * @param string|array $join Table relativity for table joining.
     * @param string|array $columns The target columns of data will be fetch.
     * @param array $where The WHERE clause to filter records.
     * @return ObjectContainer
     */
    public function single($table, $join = null, $columns = null, $where = null)
    {
        return $this->get($table, $join, $columns, $where);
    }

    /**
     * Modify data in table
     * @param string $table The table name.
     * @param array $data The data that will be modified.
     * @param array $where The WHERE clause to filter records.
     * @return int
     */
    public function update($table, $data, $where = null): int
    {
        return parent::update($table, $data, $where)->rowCount();
    }

    /**
     * Summary of Initialize
     */
    private static function Initialize(): void
    {
        $config = Config::GetConfig();

        $options = [
            "database_type" => $config['Database']['type'],
            "database_name" => $config['Database']['db_name'],
            "server" => $config['Server']['host'],
            "port" => $config['Server']['port'],
            "username" => $config['Database']['db_user'],
            "password" => $config['Database']['db_password']
        ];

        static::$connection = new static($options);
    }
}
