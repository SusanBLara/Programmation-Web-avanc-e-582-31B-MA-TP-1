<?php
require_once('Classe/CRUD.php');

class Author extends CRUD {
    protected string $table = 'author';

    // Récupère tous les auteurs par ordre alphabétique.
    public function all():array{
        return $this->select($this->table, 'name');
    }

    // Vérifie les informations et ajoute l'auteur dans la base.
    public function insertAuthor(array $data):bool|int{
        $name = '';
        if(isset($data['name'])){
            $name = trim($data['name']);
        }
        $country = '';
        if(isset($data['country'])){
            $country = trim($data['country']);
        }
        $biography = '';
        if(isset($data['biography'])){
            $biography = trim($data['biography']);
        }
        $year = '';
        if(isset($data['birth_year'])){
            $year = trim($data['birth_year']);
        }
        if($name === ''){
            throw new Exception("Le nom de l'auteur est obligatoire.");
        }
        if(mb_strlen($name) > 100 || mb_strlen($country) > 100 || strlen($biography) > 65535){
            throw new Exception("Les informations de l'auteur sont trop longues.");
        }
        $birthYear = null;
        if($year !== ''){
            $birthYear = filter_var($year, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => (int) date('Y'))));
            if($birthYear === false){
                throw new Exception("L'année de naissance est incorrecte.");
            }
        }
        $stmt = $this->prepare('SELECT id FROM author WHERE name = :name');
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        if($stmt->fetch()){
            throw new Exception('Cet auteur est déjà enregistré.');
        }
        if($country == ''){
            $country = null;
        }
        if($biography == ''){
            $biography = null;
        }
        $values = array('name' => $name, 'country' => $country,
            'birth_year' => $birthYear, 'biography' => $biography);
        return $this->insert($this->table, $values);
    }
}
