<?php
require_once('Classe/CRUD.php');

class Category extends CRUD {
    protected string $table = 'category';

    // Récupère toutes les catégories par ordre alphabétique.
    public function all():array{
        return $this->select($this->table, 'name');
    }
}
