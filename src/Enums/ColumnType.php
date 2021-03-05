<?php

namespace Exceedone\Exment\Enums;

use Exceedone\Exment\Model\CustomColumn;

class ColumnType extends EnumBase
{
    const TEXT = 'text';
    const TEXTAREA = 'textarea';
    const EDITOR = 'editor';
    const URL = 'url';
    const EMAIL = 'email';
    const INTEGER = 'integer';
    const DECIMAL = 'decimal';
    const CURRENCY = 'currency';
    const DATE = 'date';
    const TIME = 'time';
    const DATETIME = 'datetime';
    const SELECT = 'select';
    const SELECT_VALTEXT = 'select_valtext';
    const SELECT_TABLE = 'select_table';
    const YESNO = 'yesno';
    const BOOLEAN = 'boolean';
    const AUTO_NUMBER = 'auto_number';
    const IMAGE = 'image';
    const FILE = 'file';
    const USER = 'user';
    const ORGANIZATION = 'organization';

    public static function COLUMN_TYPE_CALC()
    {
        return [
            ColumnType::INTEGER,
            ColumnType::DECIMAL,
            ColumnType::CURRENCY,
        ];
    }

    public static function COLUMN_TYPE_DATETIME()
    {
        return [
            ColumnType::DATE,
            ColumnType::TIME,
            ColumnType::DATETIME,
        ];
    }

    public static function COLUMN_TYPE_DATE()
    {
        return [
            ColumnType::DATE,
            ColumnType::DATETIME,
        ];
    }

    public static function COLUMN_TYPE_ATTACHMENT()
    {
        return [
            ColumnType::IMAGE,
            ColumnType::FILE,
        ];
    }

    public static function COLUMN_TYPE_URL()
    {
        return [
            ColumnType::SELECT_TABLE,
            ColumnType::USER,
            ColumnType::ORGANIZATION,
            ColumnType::URL,
        ];
    }

    public static function COLUMN_TYPE_USER_ORGANIZATION()
    {
        return [
            ColumnType::USER,
            ColumnType::ORGANIZATION,
        ];
    }

    public static function COLUMN_TYPE_SELECT_TABLE()
    {
        return [
            ColumnType::SELECT_TABLE,
            ColumnType::USER,
            ColumnType::ORGANIZATION,
        ];
    }

    public static function COLUMN_TYPE_SELECT_FORM()
    {
        return [
            ColumnType::SELECT,
            ColumnType::SELECT_VALTEXT,
            ColumnType::SELECT_TABLE,
            ColumnType::YESNO,
            ColumnType::BOOLEAN,
        ];
    }

    public static function COLUMN_TYPE_2VALUE_SELECT()
    {
        return [
            ColumnType::YESNO,
            ColumnType::BOOLEAN,
        ];
    }

    public static function COLUMN_TYPE_MULTIPLE_ENABLED()
    {
        return [
            ColumnType::SELECT,
            ColumnType::SELECT_VALTEXT,
            ColumnType::SELECT_TABLE,
            ColumnType::USER,
            ColumnType::ORGANIZATION,
            ColumnType::FILE,
            ColumnType::IMAGE,
        ];
    }

    public static function COLUMN_TYPE_SHOW_NOT_ESCAPE()
    {
        return [
            ColumnType::URL,
            ColumnType::TEXTAREA,
            ColumnType::SELECT_TABLE,
            ColumnType::EDITOR,
            ColumnType::USER,
            ColumnType::ORGANIZATION,
        ];
    }

    public static function COLUMN_TYPE_IMPORT_REPLACE()
    {
        return [
            ColumnType::SELECT_TABLE,
            ColumnType::USER,
            ColumnType::ORGANIZATION,
        ];
    }

    public static function COLUMN_TYPE_OPERATION_ENABLE_SYSTEM()
    {
        return [
            ColumnType::DATE,
            ColumnType::TIME,
            ColumnType::DATETIME,
            ColumnType::USER,
            ColumnType::ORGANIZATION,
        ];
    }

    public static function isCalc($column_type)
    {
        return static::_isMatchColumnType($column_type, static::COLUMN_TYPE_CALC());
    }

    public static function isDate($column_type)
    {
        return static::_isMatchColumnType($column_type, static::COLUMN_TYPE_DATE());
    }

    public static function isDateTime($column_type)
    {
        return static::_isMatchColumnType($column_type, static::COLUMN_TYPE_DATETIME());
    }
    
    public static function isUrl($column_type)
    {
        return static::_isMatchColumnType($column_type, static::COLUMN_TYPE_URL());
    }
    
    public static function isAttachment($column_type)
    {
        return static::_isMatchColumnType($column_type, static::COLUMN_TYPE_ATTACHMENT());
    }
    
    public static function isUserOrganization($column_type)
    {
        return static::_isMatchColumnType($column_type, static::COLUMN_TYPE_USER_ORGANIZATION());
    }
    public static function isSelectTable($column_type)
    {
        return static::_isMatchColumnType($column_type, static::COLUMN_TYPE_SELECT_TABLE());
    }
    public static function is2ValueSelect($column_type)
    {
        return static::_isMatchColumnType($column_type, static::COLUMN_TYPE_2VALUE_SELECT());
    }
    public static function isMultipleEnabled($column_type)
    {
        return static::_isMatchColumnType($column_type, static::COLUMN_TYPE_MULTIPLE_ENABLED());
    }
    public static function isNotEscape($column_type)
    {
        return static::_isMatchColumnType($column_type, static::COLUMN_TYPE_SHOW_NOT_ESCAPE());
    }
    public static function isSelectForm($column_type)
    {
        return static::_isMatchColumnType($column_type, static::COLUMN_TYPE_SELECT_FORM());
    }
    public static function isOperationEnableSystem($column_type)
    {
        return static::_isMatchColumnType($column_type, static::COLUMN_TYPE_OPERATION_ENABLE_SYSTEM());
    }

    protected static function _isMatchColumnType($column_type, array $types) : bool
    {
        if ($column_type instanceof CustomColumn) {
            $column_type = $column_type->column_type;
        }

        return in_array($column_type, $types);
    }

    /**
     * get text is date, or datetime
     * @return ColumnType
     */
    public static function getDateType($text)
    {
        if (is_null($text)) {
            return null;
        }
        
        if (preg_match('/\d{4}-\d{2}-\d{2}$/', $text)) {
            return static::DATE;
        } elseif (preg_match('/\d{4}-\d{2}-\d{2}\h\d{2}:\d{2}:\d{2}$/', $text)) {
            return static::DATETIME;
        }
        return null;
    }


    /**
     * Get font awesome class
     *
     * @param mixed $column_type
     * @return ?string
     */
    public static function getFontAwesomeClass($column_type)
    {
        if ($column_type instanceof CustomColumn) {
            $column_type = $column_type->column_type;
        }

        switch($column_type){
            case static::TEXT:
                return 'fa-font';
            case static::TEXTAREA:
            case static::EDITOR:
                return 'fa-align-justify';
            case static::URL:
                return 'fa-link';
            case static::EMAIL:
                return 'fa-envelope-o';
            case static::INTEGER:
            case static::DECIMAL:
            case static::AUTO_NUMBER:
                return 'fa-calculator';
            case static::CURRENCY:
                return 'fa-jpy';
            case static::DATE:
            case static::TIME:
            case static::DATETIME:
                return 'fa-calendar';
            case static::SELECT:
            case static::SELECT_VALTEXT:
            case static::DATETIME:
                return 'fa-list';   
            case static::SELECT_TABLE:
            case static::SELECT_VALTEXT:
            case static::DATETIME:
                return 'fa-table';  
            case static::YESNO:
            case static::BOOLEAN:
                return 'fa-toggle-on';  
            case static::IMAGE:
                return 'fa-picture-o';  
            case static::FILE:
                return 'fa-file';  
            case static::USER:
                return 'fa-user';  
            case static::ORGANIZATION:
                return 'fa-users'; 
        }
        
        return null;
    }


    public static function getHtml($column_type)
    {
        if ($column_type instanceof CustomColumn) {
            $column_type = $column_type->column_type;
        }

        $icon = static::getFontAwesomeClass($column_type);
        return '<i class="text-center fa ' . esc_html($icon) . '" aria-hidden="true" style="width:16px;"></i>&nbsp;&nbsp;<span>' . esc_html(exmtrans("custom_column.column_type_options.{$column_type}")) . '</span>';
    }
}
