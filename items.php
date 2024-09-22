<?php
class Items
{
    public $name;
    public $price;
    public $item_id;
    public $path;

    function __construct($name, $price, $id, $path)
    {
        $this->name = $name;
        $this->price = $price;
        $this->item_id = $id;
        $this->path = $path;
    }
}
