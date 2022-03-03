<?php

namespace Exceedone\Exment\Database\Schema\Grammars;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;
use Exceedone\Exment\Model\CustomColumn;
use Exceedone\Exment\Model\CustomView;
use Illuminate\Database\Schema\Grammars\SqlServerGrammar as BaseGrammar;

class SqlServerGrammar extends BaseGrammar implements GrammarInterface
{
    use GrammarTrait;

    /**
     * Compile the query to get version
     *
     * @return string
     */
    public function compileGetVersion()
    {
        return 'SELECT @@VERSION;';
    }

    /**
     * Compile the query to show tables
     *
     * @return string
     */
    public function compileGetTableListing()
    {
        return "select table_name from INFORMATION_SCHEMA.TABLES where TABLE_TYPE='BASE TABLE'";
    }

    /**
     * Compile the query to get column difinitions
     *
     * @return string
     */
    public function compileColumnDefinitions($tableName)
    {
        return "SELECT
        TAB.name AS table_name,
        COL.name AS column_name,
        TYP.name AS [type],
        COL.is_computed AS virtual,
        COL.is_nullable AS nullable
    From
        sys.columns COL
        INNER JOIN
            sys.tables TAB
        On  COL.object_id = TAB.object_id
        INNER JOIN
            sys.types TYP
        ON  TYP.user_type_id = COL.user_type_id
    WHERE
        TAB.type = 'U'
        AND TAB.name = ?";
    }

    /**
     * Compile the query to Create Value Table
     *
     * @return string
     */
    public function compileCreateValueTable(string $tableName)
    {
        return "if object_id('{$this->wrapTable($tableName)}') is null select * into {$this->wrapTable($tableName)} from custom_values";
    }

    /**
     * Compile the query to determine the list of tables.
     *
     * @return string
     */
    public function compileCreateRelationValueTable(string $tableName)
    {
        return "if object_id('{$this->wrapTable($tableName)}') is null select * into {$this->wrapTable($tableName)} from custom_relation_values";
    }
    
    public function compileAlterIndexColumn($db_table_name, $db_column_name, $index_name, $json_column_name, CustomColumn $custom_column)
    {
        // ALTER TABLE
        if (boolval($custom_column->getOption('multiple_enabled'))) {
            $as_value = "JSON_QUERY(\"value\",'$.$json_column_name')";
        } else {
            $as_value = "JSON_VALUE(\"value\",'$.$json_column_name')";
        }

        return [
            "alter table {$db_table_name} add {$db_column_name} as {$as_value}",
            //"alter table {$db_table_name} add index {$index_name}({$db_column_name})",
        ];
    }
    
    /**
     * Create custom view's index
     *
     * @param CustomView $custom_view
     * @return array
     */
    public function compileCustomViewIndexColumn(CustomView $custom_view) : array
    {
        $info = $this->getCustomViewIndexColumnInfo($custom_view);
        $db_table_name = $info['db_table_name'];
        $pure_db_table_name = $info['pure_db_table_name'];
        $custom_view_filter_columns = $info['custom_view_filter_columns'];
        $custom_view_sort_columns = $info['custom_view_sort_columns'];
        $custom_view_filter_indexname = $info['custom_view_filter_indexname'];
        $custom_view_sort_indexname = $info['custom_view_sort_indexname'];
        $has_filter_columns = $info['has_filter_columns'];
        $has_sort_columns = $info['has_sort_columns'];

        $custom_view_filter_column = $custom_view_filter_columns->implode(',');
        $custom_view_sort_column = $custom_view_sort_columns->implode(',');

        $result = [];
        if(boolval($has_filter_columns)){
            $result[] = "drop index if exists {$custom_view_filter_indexname} on {$db_table_name}";
            $result[] = "create index {$custom_view_filter_indexname} on {$db_table_name} ({$custom_view_filter_column})";
        }
        if(boolval($has_sort_columns)){
            $result[] = "drop index if exists {$custom_view_sort_indexname} on {$db_table_name}";
            $result[] = "create index {$custom_view_sort_indexname} on {$db_table_name} ({$custom_view_sort_column})";
        }

        return $result;
    }
    
    public function compileGetIndex($tableName)
    {
        return $this->_compileGetIndex($tableName, false);
    }
    
    public function compileGetUnique($tableName)
    {
        return $this->_compileGetIndex($tableName, true);
    }

    protected function _compileGetIndex($tableName, $unique)
    {
        return "select
            COL_NAME(ic.object_id, ic.column_id) as column_name,
            i.is_unique as is_unique,
            i.name as key_name
        from
            sys.indexes AS i
        inner join
            sys.index_columns as ic
        on  i.object_id = ic.object_id
        and i.index_id = ic.index_id
        where
            i.type = 2
        and i.object_id = OBJECT_ID('{$this->wrapTable($tableName)}')
        and i.is_unique = :is_unique
        and COL_NAME(ic.object_id, ic.column_id) = :column_name";
    }

    public function compileGetConstraint($tableName)
    {
        return "SELECT 
            OBJECT_NAME([default_object_id]) AS name 
        FROM 
            sys.columns 
        WHERE 
            [object_id] = OBJECT_ID('{$this->wrapTable($tableName)}') 
        AND 
            [name] = :column_name";
    }

    /**
     *
     * @return string
     */
    public function compileDropConstraint($tableName, $contraintName)
    {
        $tableName = $this->wrapTable($tableName);
        return "ALTER TABLE $tableName DROP CONSTRAINT $contraintName";
    }

    /**
     * Compile a drop default constraint command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDropDefaultConstraint(Blueprint $blueprint, Fluent $command)
    {
        $columns = "'".implode("','", $command->columns)."'";

        $tableName = $this->getTablePrefix().$blueprint->getTable();

        $sql = "DECLARE @sql NVARCHAR(MAX) = '';";
        $sql .= "SELECT @sql += 'ALTER TABLE [dbo].[{$tableName}] DROP CONSTRAINT ' + OBJECT_NAME([default_object_id]) + ';' ";
        $sql .= 'FROM sys.columns ';
        $sql .= "WHERE [object_id] = OBJECT_ID('[dbo].[{$tableName}]') AND [name] in ({$columns}) AND [default_object_id] <> 0;";
        $sql .= 'EXEC(@sql)';

        return $sql;
    }
}
