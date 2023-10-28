<?php

namespace si_Campaigner\Sugar\Helpers;

/**
 * This class performs some db operations which can't be done through beans
 */
class DBHelper
{
    /**
     * Given a column query it will execute and return back result
     * @param  string    query
     * @return array      true     if exists, false otherwise
     */
    public static function executeQuery($query)
    {
        try {
            $GLOBALS['log']->debug('si_Campaigner Query: ' . $query);
            return $GLOBALS['db']->query($query);
        } catch (\Exception $ex) {
            $GLOBALS['log']->fatal("si_Campaigner Exception in " . __FILE__ . ":" . __LINE__ . ": " . $ex->getMessage());
            return false;
        }
    }

    /**
     * This function executes update query
     * @param  string $table name of the table
     * @param  string|array  $fields associative array of fields to be updated
     * @param  array  $where associative array of fields to be matched in where clause.
     * @return array $res2 array of results
     * @access public
     */
    public static function select($table, $fields = '*', $where = [])
    {
        try {
            $sql = "SELECT ";
            if (is_string($fields))
                $sql .= $fields;
            else if (is_array($fields) && !empty($fields))
                $sql .= implode(", ", $fields);
            else
                $sql .= '*';
            $sql .= " FROM $table";
            $sql .= self::whereMaker($where);
            $sql = rtrim($sql);
            $res = self::executeQuery($sql);
            $res2 = [];
            while ($row = $GLOBALS['db']->fetchByAssoc($res)) {
                $res2[] = $row;
            }
            return $res2;
        } catch (\Exception $ex) {
            $GLOBALS['log']->fatal("si_Campaigner Exception in " . __FILE__ . ":" . __LINE__ . ": " . $ex->getMessage());
            return false;
        }
    }

    /**
     * This function executes update query
     * @param  string $table name of the table
     * @param  string|array  $fields associative array of fields to be updated
     * @param  array  $where associative array of fields to be matched in where clause.
     * @return array $res2 array of results
     * @access public
     */
    public static function delete($table, $where = [])
    {
        try {
            $sql = "DELETE FROM $table";
            $sql .= self::whereMaker($where);
            $sql = rtrim($sql);
            return self::executeQuery($sql);
        } catch (\Exception $ex) {
            $GLOBALS['log']->fatal("si_Campaigner Exception in " . __FILE__ . ":" . __LINE__ . ": " . $ex->getMessage());
            return false;
        }
    }

    /**
     * This function executes update query
     * @param  string $table name of the table
     * @param  array  $fields associative array of fields to be updated
     * @param  array  $where associative array of fields to be matched in where clause.
     * @access public
     */
    public static function update($table, $fields, $where = [])
    {
        try {
            $sql = "UPDATE $table SET";
            foreach ($fields as $field => $value)
                $sql .= " " . $field . " = '" . $value . "',";
            $sql = rtrim($sql, ',');
            $sql .= self::whereMaker($where);
            $sql = rtrim($sql);
            return self::executeQuery($sql);
        } catch (\Exception $ex) {
            $GLOBALS['log']->fatal("si_Campaigner Exception in " . __FILE__ . ":" . __LINE__ . ": " . $ex->getMessage());
            return false;
        }
    }

    /**
     * Prepares a where statement based on an associative array
     * @param array $where associative array of fields to be matched in where clause. This array contains operator to be used against a field and its value. @example $where = ['id' => ['=', '123'], 'date_modified' => ['>', '2021-01-01 00:00:00'], 'industry' => ['operator' => 'OR', 'IN', ['IT', 'Health', 'Education']]] will result in WHERE id = '123' AND date_modified > '2021-01-01 00:00:00' OR industry IN '('IT','Health','Education')'
     * @return string $sql a where clause string for SQL Query
     * @access public
     */
    public static function whereMaker($where = [])
    {
        if (!$where)
            return '';

        while (!ltrim($rhs = self::rhsParser($where[key($where)][1]))) {
            unset($where[key($where)]);
        }
        if (ltrim($rhs)) {
            $sql = " WHERE " . key($where) . " " . $where[key($where)][0] . $rhs;
            unset($where[key($where)]);
        }
        foreach ($where as $field => $value) {
            $rhs = self::rhsParser($where[$field][1]);
            if (ltrim($rhs)) {
                $sql .= $value['operator'] ?? "AND";
                $sql .= " " . $field . " " . $where[$field][0] . $rhs;
            }
        }
        return $sql;
    }

    /**
     * Prepares right hand side of an assignment or comparison based on the type of argument
     * @param  int|string|array $value value to be compared or assigned
     * @return string $sql part of sql statement that can be placed in front of an operator
     * @access public
     */
    public static function rhsParser($value)
    {
        $sql = " ";
        if (is_array($value) && !empty($value)) {
            $sql .=  "(";
            foreach ($value as $val) {
                if (is_numeric($val))
                    $sql .= $val . ',';
                else
                    $sql .= "'" . $val . "',";
            }
            $sql = rtrim($sql, ',');
            $sql .= ")";
        } else if (is_numeric($value))
            $sql .= $value;
        else if (is_string($value)) {
            if (strtolower($value) == 'null')
                $sql .= 'NULL';
            else
                $sql .= "'" . $value . "'";
        }
        return $sql . " ";
    }


    /**
     * Given a column name and table name, check if that column exists in the table
     * @param  string    $table   table name to look in
     * @param  string    $column  column to check
     * @return bool      true     if exists, false otherwise
     */
    public static function columnExists($table, $column)
    {
        try {
            $cols = $GLOBALS['db']->get_columns($table);
            if (is_array($cols)) {
                if (isset($cols[$column]))
                    return true;
                else
                    return false;
            } else
                return false;
        } catch (\Exception $ex) {
            $GLOBALS['log']->fatal("si_Campaigner Exception in " . __FILE__ . ":" . __LINE__ . ": " . $ex->getMessage());
            return false;
        }
    }
}
