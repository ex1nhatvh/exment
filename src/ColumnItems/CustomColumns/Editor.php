<?php

namespace Exceedone\Exment\ColumnItems\CustomColumns;

use Exceedone\Exment\ColumnItems\CustomItem;
use Exceedone\Exment\Form\Field;
use Exceedone\Exment\Model\File as ExmentFile;
use Exceedone\Exment\Model\System;
use Exceedone\Exment\Model\Define;
use Exceedone\Exment\Validator;
use Illuminate\Support\Facades\Storage;

class Editor extends CustomItem
{
    public function saving()
    {
        if (is_nullorempty($this->value)) {
            return;
        }

        $value = $this->savedFileInEditor($this->value);

        return strval($value);
    }

    protected function _html($v)
    {
        $text = $this->_text($v);
        if (is_null($text)) {
            return null;
        }

        if (boolval(array_get($this->options, 'grid_column'))) {
            // if grid, remove tag and omit string
            $text = get_omitted_string(strip_tags($text));
        }
        
        return  '<div class="show-tinymce">'.replaceBreak(html_clean($text), false).'</div>';
    }
    
    protected function getAdminFieldClass()
    {
        return Field\Tinymce::class;
    }
    
    protected function setAdminOptions(&$field, $form_column_options)
    {
        $options = $this->custom_column->options;
        $field->rows(array_get($options, 'rows', 6));
    }
    
    protected function setValidates(&$validates, $form_column_options)
    {
        // value string
        $validates[] = new Validator\StringNumericRule();
    }


    protected function savedFileInEditor($value)
    {
        if (is_nullorempty($value)) {
            return $value;
        }
        // find <img alt="" src="data:"> tag
        preg_match_all('/\<img([^\>]*?)src="(?<src>.*?)"(.*?)\>/u', $value, $matches);
        if(is_nullorempty($matches)){
            return $value;
        }

        // replace src="data:" and save file
        foreach($matches[0] as $match){
            // group
            preg_match('/\<img([^\>]*?)src="(?<src>.*?)"(.*?)\>/u', $match, $matchSrc);

            if(is_nullorempty($matchSrc)){
                continue;
            }

            $src = array_get($matchSrc, 'src');
            $filename = pathinfo($src, PATHINFO_FILENAME);

            $exists = Storage::disk(Define::DISKNAME_TEMP_UPLOAD)->exists($filename);
        
            if (!$exists) {
                continue;
            }

            // get temporary file data
            $file = Storage::disk(Define::DISKNAME_TEMP_UPLOAD)->get($filename);            
            // save file info
            $exmentfile = ExmentFile::put(path_join($this->custom_table->table_name, make_uuid()), $file);
            // delete temporary file
            Storage::disk(Define::DISKNAME_TEMP_UPLOAD)->delete($filename);            

            // set request session to save this custom_value's id and type into files table.
            $file_uuids = System::requestSession(Define::SYSTEM_KEY_SESSION_FILE_UPLOADED_UUID) ?? [];
            $file_uuid = [
                'uuid' => $exmentfile->uuid,
                'column_name' => $this->custom_column->column_name,
                'custom_table' => $this->custom_table,
                'path' => $exmentfile->path
            ];
            $file_uuids[] = $file_uuid;
            System::requestSession(Define::SYSTEM_KEY_SESSION_FILE_UPLOADED_UUID, $file_uuids);

            // replace src to url
            preg_match('/\<img([^\>]*?)src="(?<src>.*?)"(.*?)\>/u', $match, $matchSrc);
            if($matchSrc){
                $value = str_replace(array_get($matchSrc, 'src'), ExmentFile::getUrl($exmentfile), $value);
            }
            $this->custom_value->file_uuids($file_uuid);
        }

        return $value;
    }

    protected function getExtention($type){
        if(is_nullorempty($type)){
            return null;
        }
        
        $types = [
            'image/gif' => 'gif', 
            'image/jpeg'=>'jpg', 
            'image/png'=>'png', 
            'image/svg+xml'=>'svg', 
            'image/bmp'=>'bmp', 
        ];
        foreach($types as $t => $ext){
            if(isMatchString($t, $type)){
                return ".{$ext}";
            }
        }

        return null;
    }
}
