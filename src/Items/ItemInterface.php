<?php

namespace Exceedone\Exment\Items;

interface ItemInterface 
{
    /**
     * get column key name 
     */
    public function name();

    /**
     * get value. (only this column's value. not custom_value) 
     */
    public function value();

    /**
     * get text(for display) 
     */
    public function text();

    /**
     * get html
     */
    public function html();

    /**
     * get or set value's id.
     */
    public function id($id = null);
    
    /**
     * get or set header label.
     */
    public function label($label = null);

    /**
     * set custom value
     */
    public function setCustomValue($custom_value);

    /**
     * prepare value
     */
    public function prepare();

    /**
     * get item model
     */
    public static function getItem(...$options);
}
