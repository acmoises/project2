<?php

namespace Model;

class Menu extends ActiveRecord
{
    protected static $tabla = 'menus';
    protected static $columnasDB = ['id', 'name', 'description', 'parent_id', 'created_at'];

    public $id;
    public $name;
    public $description;
    public $parent_id;
    public $created_at;
    public $parent_name;

}