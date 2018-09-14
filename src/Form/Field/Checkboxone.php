<?php

namespace Exceedone\Exment\Form\Field;

use Encore\Admin\Form\Field;

class Checkboxone extends Field
{
    protected $view = 'exment::form.field.checkboxone';

    protected static $css = [
        '/vendor/laravel-admin/AdminLTE/plugins/iCheck/all.css',
    ];

    protected static $js = [
        '/vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js',
    ];

    protected $check_label = '';
    protected $check_value = '';

    /**
     * Set options.
     *
     * @param array|callable|string $options
     *
     * @return $this|mixed
     */
    public function option($option = array())
    {
        if(count($option) == 0){
            return $this;
        }
        foreach($option as $k => $v){
            $this->check_value = $k;
            $this->check_label = $v;
            break;
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->script = "$('{$this->getElementClassSelector()}').iCheck({checkboxClass:'icheckbox_minimal-blue'});";

        return parent::render()->with(['check_value' => $this->check_value, 'check_label' => $this->check_label]);
    }
}
