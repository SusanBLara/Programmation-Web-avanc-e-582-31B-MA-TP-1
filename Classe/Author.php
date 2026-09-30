<?php
require_once('Classe/CRUD.php');

class Author extends CRUD {
    protected string $table = 'author';

    public function all():array{
        return $this->select($this->table, 'name');
    }
}
