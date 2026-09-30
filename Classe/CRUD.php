<?php

class CRUD extends PDO {

    public function __construct(){
        parent::__construct(
            'mysql:host=localhost; dbname=librairie_enfants; port=3306; charset=utf8mb4', 'root', 'admin');

    }

    public function select(string $table, $field = "id", $order = "ASC"):array{
        $sql = "SELECT * FROM $table ORDER BY $field $order";
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    public function selectId(string $table, int|string $value, $field = 'id'):bool|array{
        $sql = "SELECT * FROM $table WHERE $field = :$field";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":$field", $value);
        $stmt->execute();

        $count = $stmt->rowCount();
        if($count == 1){
            return $stmt->fetch();
        }else{
            return false;
        }
    }

    public function insert(string $table, array $data):bool|int{
        $fieldName = implode(', ', array_keys($data));
        $fieldBindValue = ":".implode(', :', array_keys($data));

        $sql = "INSERT INTO $table ($fieldName) VALUES ($fieldBindValue);";
        $stmt = $this->prepare($sql);

        foreach($data as $key=>$value){
            $stmt->bindValue(":$key", $value);
        }

        if($stmt->execute()){
            if($table == 'book_author'){
                // Cette table n'a pas d'identifiant auto-incrémenté à retourner.
                return true;
            }else{
                return $this->lastInsertId();
            }
        }else{
            return false;
        }
    }

    public function update(string $table, array $data, $field = 'id'):bool{
        $fieldName = null;
        foreach($data as $key=>$value){
            $fieldName .= "$key = :$key, ";
        }

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
