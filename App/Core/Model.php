<?php

namespace App\Core;

use PDO;
use Exception;
use PDOException;
use JsonSerializable;
use Database\Connection;
use Support\Objects\Property;
use Database\Query\QueryBuilder;
use Support\Objects\ObjectContainer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Summary of Model
 */
abstract class Model implements JsonSerializable
{
    use QueryBuilder;

    /**
     * Summary of $attributes
     * @var ObjectContainer
     */
    protected $attributes;

    /**
     * Summary of $connection
     * @var Connection
     */
    protected $connection;

    /**
     * Summary of $fillable
     * @var string[]
     */
    protected $fillable = [];

    /**
     * Summary of $incrementing
     * @var bool
     */
    protected $incrementing = true;

    /**
     * Summary of $keyType
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Summary of $perPage
     * @var int
     */
    protected $perPage = 5;

    /**
     * Summary of $primaryKey
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Summary of $relationObjects
     * @var Model
     */
    protected $relationObject;

    /**
     * Summary of $rules
     * @var array
     */
    protected $rules = [];

    /**
     * Summary of $table
     * @var string
     */
    protected $table;

    /**
     * Summary of $validator
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Summary of $errors
     * @var array
     */
    private $errors = [];

    private $disableContraints = false;

    /**
     * Summary of $violations
     * @var ConstraintViolationList
     */
    private $violations;

    /**
     * @var array
     */
    private $violationsArray = [];

    /**
     * Summary of AddContraint
     * @param string $propertyName
     * @param Constraint[] $constraint
     * @return boolean
     */
    public function AddConstraint(string $propertyName, Constraint...$constraint): bool
    {
        if ($this->attributes->PropertyExists($propertyName))
        {
            $this->rules[strtolower($propertyName)] = $constraint;

            return true;
        }

        return false;
    }

    public function RemoveContraintsFor(string ...$names)
    {
        foreach($names as $name)
        {
            if(array_key_exists(strtolower($name),$this->rules))
            {
                unset($this->rules[strtolower($name)]);
            }
        }
    }

    public function DisableContraints(bool $on=true):void
    {
        $this->disableContraints = $on;
    }

    /**
     * @return mixed
     */
    public function AsArray()
    {
        return $this->attributes->ToArray();
    }

    /**
     * Summary of Create
     * @param Connection $conn
     * @param string[]|Property[] $attributes
     */
    public static function Create(Connection $conn, ...$attributes): ?Model
    {
        $ref = new static(...$attributes);

        $ref->SetConnection($conn);

        return $ref;
    }

    /**
     * Summary of Delete
     * @return boolean
     */
    public function Delete(): bool
    {
        $rows = 0;

        try
        {
            $rows = $this->connection->delete($this->table, [$this->primaryKey => $this->__get($this->primaryKey)]);
        }
        catch (PDOException $ex)
        {
            $this->AddError($ex);
        }

        if($rows == 0)
        {
            $this->AddError(new Exception('No row was affected'));
        }

        return $rows > 0;
    }

    /**
     * Summary of Fill
     * @param mixed $attributes
     * @return Model
     */
    public function Fill($attributes): Model
    {

        foreach ($attributes as $field => $value)
        {
            if ($this->IsFillable($field) && $this->attributes->PropertyExists($field))
            {
                $this->$field = $value;
            }
        }

        return $this;
    }

    private function RemoveUnknowColumns(array &$attributes):void
    {
        foreach ($attributes as $property=>$value)
        {
        	if(!$this->attributes->PropertyExists($property))
            {
                unset($attributes[$property]);
            }
        }
    }

    /**
     * Summary of GetAttributes
     * @return array
     */
    public function GetAttributes()
    {
        return array_keys($this->attributes->ToArray());
    }

    /**
     * Summary of GetConstraintViolations
     * @return ConstraintViolationInterface[]
     */
    public function GetConstraintViolations()
    {
        return $this->violations->getIterator();
    }

    public function GetConstraintViolationsArray(): array
    {
        return $this->violationsArray;
    }

    /**
     * Summary of GetErrors
     * @return array
     */
    public function GetErrors(): array
    {
        return $this->errors;
    }

    /**
     * Summary of GetPropertyConstraints
     * @return Constraint[]
     */
    public function GetPropertyConstraints(string $propertyName)
    {
        return $this->rules[strtolower($propertyName)];
    }

    /**
     * Summary of HasErrors
     * @return boolean
     */
    public function HasErrors(): bool
    {
        return !empty($this->errors) && count($this->errors) !=0;
    }

    /**
     * Summary of MakeFromDatabase
     * @param Connection $connection
     * @param mixed $table
     * @return Model|null
     */
    public static function MakeFromDatabase(Connection $connection, $table): ?Model
    {
        try
        {
            $statement = $connection->pdo->query("describe $table");

            $statement->execute();

            $results = $statement->fetchAll(PDO::FETCH_ASSOC);

            $properties = [];

            $pk = '';

            foreach ($results as $column)
            {

                $properties[] = Property::Property($column['Field']);

                if ($column['Key'] === 'PRI')
                {
                    $pk = $column['Field'];
                }

            }

            $obj = new static(...$properties);

            $obj->table = $table;

            $obj->primaryKey = $pk;

            $obj->fillable = [];

            $obj->connection = $connection;

            return $obj;

        }
        catch (PDOException $ex)
        {
            return null;
        }
    }

    /**
     * Summary of Refresh
     * @return boolean
     */
    public function Refresh(bool $addError=false): bool
    {
        if ($results = $this->Single('*', [$this->primaryKey => $this->__get($this->primaryKey)]))
        {
            if(!$results->IsEmpty())
            {
                $this->attributes = $results;

                if($addError)
                {
                    $this->AddError(new Exception('Duplicate Key'));
                }

                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * Summary of Save
     * @return boolean
     */
    public function Save(): bool
    {
        if ($this->Validate())
        {

            $rows = 0;

            $this->errors = [];

            try
            {
                if ($this->incrementing && $this->keyType === 'int')
                {
                    $filter = $this->attributes->ToArray();

                    $filter[$this->primaryKey] = null;

                    $rows = $this->connection->insert($this->table, $filter);
                }
                else
                {
                    $rows = $this->connection->insert($this->table, $this->attributes->ToArray());
                }

            }
            catch (PDOException $ex)
            {
                $this->AddError($ex);
            }

            if($rows == 0)
            {
                $this->AddError(new Exception('No row was affected'));
            }

            return $rows > 0;
        }

        return false;
    }

    /**
     * Summary of SaveOrUpdate
     * @return boolean
     */
    public function SaveOrUpdate(array $attributes = [], bool $autoFill = true): bool
    {
        $this->Fill($attributes);

        $this->RemoveUnknowColumns($attributes);

        if ($this->Validate())
        {

            $rows = 0;

            try
            {
                $rows = $this->connection->insert($this->table, $this->attributes->ToArray());
            }
            catch (PDOException $ex)
            {
                if ($ex->getCode() === "23000")
                {
                    try
                    {
                        if (!$autoFill)
                        {
                            $rows = $this->connection->update($this->table, $attributes, [$this->primaryKey => $this->__get($this->primaryKey)]);
                        }
                        else
                        {
                            $rows = $this->connection->update($this->table, $this->attributes->ToArray(), [$this->primaryKey => $this->__get($this->primaryKey)]);
                        }
                    }
                    catch (PDOException $ex)
                    {
                        $this->AddError($ex);
                    }
                }
            }

            if($rows == 0)
            {
                $this->AddError(new Exception('No row was affected'));
            }

            return $rows > 0;
        }

        return false;
    }

    /**
     * Summary of SetConnection
     * @param Connection $connection
     */
    public function SetConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * Summary of ToJson
     * @return string
     */
    public function ToJson(): string
    {
        return $this->attributes->ToJson();
    }

    /**
     * Summary of Update
     * @param mixed $attributes
     * @return boolean
     */
    public function Update($attributes): bool
    {
        return $this->SaveOrUpdate($attributes, false);
    }

    /**
     * Summary of Validate
     * @return boolean
     */
    public function Validate(): bool
    {
        $this->violations = new ConstraintViolationList();

        if(!$this->disableContraints)
        {

            foreach ($this->attributes->getIterator() as $property)
            {
                if (array_key_exists(strtolower($property->PropertyName()), $this->rules))
                {
                    foreach ($this->validator->validate($property->Value(), $this->GetPropertyConstraints($property->PropertyName())) as $violation)
                    {
                        $this->violations->add($violation);
                        $this->violationsArray[$property->PropertyName()][] = $violation->getMessage();
                    }
                }
            }

            if (0 !== count($this->violations))
            {
                return false;
            }

            return true;
        }

        return true;
    }

    /**
     * Summary of __construct
     * @param string[]|Property[] $attributes
     */
    public function __construct(...$attributes)
    {
        if (is_array($attributes) && !empty($attributes))
        {
            if ($attributes[0] instanceof Property)
            {
                $this->attributes = new ObjectContainer(...$attributes);
            }
            if (is_string($attributes[0]))
            {
                $nullProperties = array_map(function (string $property)
                {
                    return Property::Property($property);
                }, $attributes);

                $this->attributes = new ObjectContainer(...$nullProperties);
            }
        }
        else
        {
            $this->attributes = new ObjectContainer();
        }

        $this->validator = Validation::createValidator();

        $this->violations = new ConstraintViolationList();
    }

    /**
     * Summary of __get
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->attributes->PropertyExists($key) ? $this->attributes->GetProperty($key)->Value() : null;
    }

    /**
     * Summary of __set
     * @param string $key
     * @param mixed|Property $value
     * @return boolean
     */
    public function __set($key, $value): bool
    {
        return $this->attributes->SetProperty($key, $value);
    }

    /**
     * Specify data which should be serialized to JSON
     * Serializes the object to a value that can be serialized natively by json_encode() .
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->attributes->ToArray();
    }

    /**
     * Summary of AddError
     * @param Exception $exception
     */
    private function AddError(Exception $exception,$log=[]): void
    {
        $this->errors[] = ['Code:' => $exception->getCode(), 'Error:' => $exception->getMessage(), 'Line:' => $exception->getLine(), 'File:' => $exception->getFile(),'log'=>$log];
    }

    /**
     * Summary of IsFillable
     * @param string $key
     * @return boolean
     */
    private function IsFillable(string $key)
    {

        if ($this->fillable === [])
        {
            return true;
        }

        $lowerKeys = array_map(function ($val)
        {
            return $this->LowerCaseKeys($val);
        }, $this->fillable);

        return in_array(strtolower($key), $lowerKeys);
    }

    /**
     * Summary of LowerCaseKeys
     * @param mixed $value
     * @return string
     */
    private function LowerCaseKeys($value): string
    {
        return strtolower($value);
    }
}
