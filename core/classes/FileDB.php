<?php

namespace Core;
/**
 * Class FileDB
 */
class FileDB
{
    private string $file_name;
    private array $data;

    /**
     * FileDB constructor.
     *
     * @param $file_name
     */
    public function __construct($file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * Set $data variable
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data ?? [];
    }

    /**
     * Get $data variable
     *
     * @param array $data_array
     */
    public function setData(array $data_array): void
    {
        $this->data = $data_array;
    }

    /**
     * Save JSON representation of an array to database file
     *
     * @return bool
     */
    public function save(): bool
    {
        $data = json_encode($this->getData());
        $bytes_written = file_put_contents($this->file_name, $data);

        return $bytes_written !== false;
    }

    /**
     * Get data from database file and decode to array
     *
     * @return bool
     */
    public function load(): bool
    {
        if (file_exists($this->file_name)) {
            $data = file_get_contents($this->file_name);

            if ($data !== false) {
                $this->setData(json_decode($data, true) ?? []);
            }

            return true;
        }

        $this->setData([]);

        return false;
    }

    /**
     * Create a new array with $table_name inside of $data
     *
     * @param string $table_name
     * @return bool
     */
    public function createTable(string $table_name): bool
    {
        if (!$this->tableExists($table_name)) {
            $this->data[$table_name] = [];

            return true;
        }

        return false;
    }

    /**
     * Checks if this index already exists in data.
     *
     * @param string $table_name
     * @return bool
     */
    public function tableExists(string $table_name): bool
    {
        return array_key_exists($table_name, $this->getData());
    }

    /**
     * Deletes table with index
     *
     * @param $table_name
     * @return bool
     */
    public function dropTable(string $table_name): bool
    {
        if ($this->tableExists($table_name)) {
            unset($this->data[$table_name]);

            return true;
        }

        return false;
    }

    /**
     * Truncate table, leave index
     *
     * @param $table_name
     * @return bool
     */
    public function truncateTable(string $table_name): bool
    {
        if ($this->tableExists($table_name)) {
            $this->data[$table_name] = [];

            return true;
        }

        return false;
    }

    /**
     * Add rows
     *
     * @param $table_name
     * @param $row
     * @param null $row_id
     * @return int|string|null
     */
    public function insertRow(string $table_name, array $row, $row_id = null)
    {
        if ($row_id !== null) {
            if (!$this->rowExists($table_name, $row_id)) {
                $this->data[$table_name][$row_id] = $row;
                return false;
            }
        } else {
            $this->data[$table_name][] = $row;
            $row_id = array_key_last($this->data[$table_name]);
        }

        return $row_id;
    }

    /**
     * Check if row exists.
     *
     * @param string $table_name
     * @param $row_id
     * @return bool
     */
    public function rowExists(string $table_name, $row_id): bool
    {
        return array_key_exists($row_id, $this->data[$table_name]);
    }

    /**
     * Update table $row by selecting $row_id
     *
     * @param string $table_name
     * @param $row_id
     * @param array $row
     * @return bool
     */
    public function updateRow(string $table_name, $row_id, array $row): bool
    {
        if ($this->rowExists($table_name, $row_id)) {
            $this->data[$table_name][$row_id] = $row;

            return true;
        }

        return false;
    }

    /**
     * Delete row, if exists
     *
     * @param string $table_name
     * @param $row_id
     * @return bool
     */
    public function deleteRow(string $table_name, $row_id): bool
    {
        if ($this->rowExists($table_name, $row_id)) {
            unset($this->data[$table_name][$row_id]);

            return true;
        }

        return false;
    }

    /**
     * Returns row from the table
     *
     * @param string $table_name
     * @param $row_id
     * @return false|array
     */
    public function getRowById(string $table_name, $row_id)
    {
        if ($this->rowExists($table_name, $row_id)) {
            return $this->data[$table_name][$row_id];
        }

        return false;
    }

    /**
     * Finds rows in the table by conditions
     *
     * @param string $table_name
     * @param array $conditions
     * @return array
     */
    public function getRowsWhere(string $table_name, array $conditions = []): array
    {
        $results = [];

        foreach ($this->data[$table_name] as $row_id => $row) {
            $found = true;

            foreach ($conditions as $condition_id => $condition_value) {
                // Tikrinam ar eilutės stulpelis $condition_id indeksu
                // atitinka su $condition_value
                if ($row[$condition_id] !== $condition_value) {
                    $found = false;
                    break;
                }
            }

            if ($found) {
                // įdedam rastą eilutę į $results
                $results[$row_id] = $row;
            }
        }

        return $results;
    }

    /**
     * Return first row of the table
     *
     * @param string $table_name
     * @param array $conditions
     * @return false|array
     */
    public function getRowWhere(string $table_name, array $conditions = [])
    {
        foreach ($this->data[$table_name] as $row_id => $row) {
            $found = true;

            foreach ($conditions as $condition_id => $condition_value) {

                if ($row[$condition_id] !== $condition_value) {
                    $found = false;
                    break;
                }
            }

            if ($found) {
                return $row;
            }
        }

        return false;
    }
}
