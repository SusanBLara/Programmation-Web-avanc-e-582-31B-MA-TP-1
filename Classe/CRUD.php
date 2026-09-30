<?php

class CRUD extends PDO {

    // Ouvre la connexion à la base de données de la librairie.
    public function __construct(){
        parent::__construct(
            'mysql:host=localhost; dbname=librairie_enfants; port=3306; charset=utf8mb4', 'root', '');

    }
    // Récupère toutes les lignes d'une table, triées selon le champ choisi.
    public function select(string $table, $field = "id", $order = "ASC"):array{
        $sql = "SELECT * FROM $table ORDER BY $field $order";
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }
    // Cherche une ligne par son id, ou par un autre champ si on le précise.
    public function selectId(string $table, int|string $value, $field = 'id'):bool|array{
        $sql = "SELECT * FROM $table WHERE $field = :$field";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":$field", $value);
        $stmt->execute();

        // On retourne le résultat seulement si une seule ligne correspond.
        $count = $stmt->rowCount();
        if($count == 1){
            return $stmt->fetch();
        }else{
            return false;
        }
    }
    // Ajoute les données dans la table choisie.
    public function insert(string $table, array $data):bool|int{
        // Les clés du tableau donnent les colonnes et les paramètres à préparer.
        $fieldName = implode(', ', array_keys($data));
        $fieldBindValue = ":".implode(', :', array_keys($data));

        $sql = "INSERT INTO $table ($fieldName) VALUES ($fieldBindValue);";
        $stmt = $this->prepare($sql);

        foreach($data as $key=>$value){
            $stmt->bindValue(":$key", $value);
        }

        if($stmt->execute()){
            if($table == 'book_author'){
                // book_author n'a pas d'identifiant auto-incrémenté, on retourne donc true.
                return true;
            }else{
                return $this->lastInsertId();
            }
        }else{
            return false;
        }
    }
    // Met à jour les données en utilisant l'id, ou un autre champ fourni dans le tableau.
    public function update(string $table, array $data, $field = 'id'):bool{
        // Construit la liste des colonnes à modifier à partir des données reçues.
        $fieldName = null;
        foreach($data as $key=>$value){
            $fieldName .= "$key = :$key, ";
        }
        // Retire la dernière virgule avant de compléter la requête.
        $fieldName = rtrim($fieldName, ', ');
        $sql = "UPDATE $table SET $fieldName WHERE $field = :$field;";

        $stmt = $this->prepare($sql);

        foreach($data as $key=>$value){
            $stmt->bindValue(":$key", $value);
        }

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    // Supprime les lignes avec l'id donné, ou avec un autre champ si on le précise.
    public function delete(string $table, int|string $value, $field = 'id'):bool{
        $sql = "DELETE FROM $table WHERE $field = :$field";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":$field", $value);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
}
