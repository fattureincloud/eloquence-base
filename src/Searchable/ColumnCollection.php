<?php

namespace Sofa\Eloquence\Searchable;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

class ColumnCollection implements ArrayAccess, IteratorAggregate
{
    /** @var array */
    protected $columns = [];

    /**
     * Create new searchable columns collection.
     *
     * @param array $columns
     */
    public function __construct(array $columns = [])
    {
        foreach ($columns as $column) {
            $this->add($column);
        }
    }

    /**
     * Get columns as plain array.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Add column to the collection.
     *
     * @param Column $column
     */
    public function add(Column $column)
    {
        $this->columns[$column->getMapping()] = $column;
    }

    /**
     * Get array of qualified columns names.
     *
     * @return array
     */
    public function getQualifiedNames()
    {
        return array_map(function ($column) {
            return $column->getQualifiedName();
        }, $this->columns);
    }

    /**
     * Get array of tables names.
     *
     * @return array
     */
    public function getTables()
    {
        return array_unique(array_map(function ($column) {
            return $column->getTable();
        }, $this->columns));
    }

    /**
     * Get array of columns mappings and weights.
     *
     * @return array
     */
    public function getWeights()
    {
        $weights = [];

        foreach ($this->columns as $column) {
            $weights[$column->getMapping()] = $column->getWeight();
        }

        return $weights;
    }

    /**
     * Get array of columns mappings.
     *
     * @return array
     */
    public function getMappings()
    {
        return array_map(function ($column) {
            return $column->getMapping();
        }, $this->columns);
    }

    /**
     * Check if element exists at given offset.
     *
     * @param  string  $key
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return array_key_exists($key, $this->columns);
    }

    /**
     * Get element at given offset.
     *
     * @param  string $key
     * @return Column
     */
    public function offsetGet($key): Column
    {
        return $this->columns[$key];
    }

    /**
     * Set element at given offset.
     *
     * @param  string $key    [description]
     * @param Column $column
     * @return void
     */
    public function offsetSet($key, $column): void
    {
        $this->add($column);
    }

    /**
     * Unset element at given offset.
     *
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key): void
    {
        unset($this->columns[$key]);
    }

    /**
     * Get an iterator for the columns.
     *
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->columns);
    }
}
