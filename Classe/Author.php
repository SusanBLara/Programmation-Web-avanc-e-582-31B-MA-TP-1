<?php
require_once('Classe/CRUD.php');

class Author extends CRUD {
    protected string $table = 'author';

    // Récupère tous les auteurs par ordre alphabétique.
    public function all():array{
        return $this->select($this->table, 'name');
    }
}
