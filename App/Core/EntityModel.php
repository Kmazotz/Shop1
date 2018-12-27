<?php

namespace App\Core;

use App\Core\Model;

/**
 * EntityModel short summary.
 *
 * EntityModel description.
 *
 * @version 1.0
 * @author Usuario
 */
final class EntityModel extends Model
{
    /**
     * @param bool $value
     * @return mixed
     */
    public function Autoincrement(bool $value = true): EntityModel
    {
        $this->incrementing = $value;

        return $this;
    }

    /**
     * @param string $attributes
     * @return mixed
     */
    public function Fillables(string...$attributes): EntityModel
    {
        $this->fillable = $attributes;

        return $this;
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function KeyType(string $value = 'int'): EntityModel
    {
        $this->keyType = $value;

        return $this;
    }

    /**
     * @param int $page
     * @return mixed
     */
    public function SetPerPage(int $page)
    {
        $this->perPage = $page;

        return $this;
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function SetPrimaryKey(string $value): EntityModel
    {
        $this->primaryKey = $value;

        return $this;
    }

    /**
     * @param string $table
     * @return mixed
     */
    public function SetTable(string $table): EntityModel
    {
        $this->table = $table;

        return $this;
    }
}
