<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/26
 * Time: 10:36
 */

namespace app\common\services;


class DataHelper
{

    public static function getDicByRealateId($data, $relate_model, $id_column, $pk_column, $name_columns = [])
    {

        $ids = [];
        $names = [];
        foreach ($data as $item) {
            $ids[] = $item[$id_column];
        }
        $rel_data = $relate_model::findAll([$pk_column => array_unique($ids)]);
        foreach ($rel_data as $rel) {
            $map_item = [];
            if ($name_columns && is_array($name_columns)) {
                foreach ($name_columns as $name_column) {
                    $map_item[$name_column] = $rel->$name_column;
                }
            }
            $names[$rel->$pk_column] = $map_item;
        }
        return $names;
    }
}
