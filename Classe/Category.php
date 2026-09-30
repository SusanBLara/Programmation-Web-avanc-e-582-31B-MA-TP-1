<?php
require_once('Classe/CRUD.php');

class Category extends CRUD {
    protected string $table = 'category';

    public function all():array{
        return $this->select($this->table, 'name');
    }
}
