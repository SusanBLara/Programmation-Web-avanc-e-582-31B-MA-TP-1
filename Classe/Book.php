<?php
require_once('Classe/CRUD.php');

class Book extends CRUD {
    protected string $table = 'book';

    public function all():array{
        return $this->select($this->table, 'title');
    }

    public function find(int|string $id):array|bool{
        return $this->selectId($this->table, $id);
    }

    public function authors(int $id):array{
        // Garde seulement les auteurs associés à ce livre.
        $authors = $this->select('author', 'name');
        $relations = $this->select('book_author', 'book_id');
        $bookAuthors = array();
        foreach($authors as $author){
            foreach($relations as $relation){
                if($relation['book_id'] == $id && $relation['author_id'] == $author['id']){
                    $bookAuthors[] = $author;
                }
            }
        }
        return $bookAuthors;
    }

    private function bookData(array $data):array{
        if(!isset($data['title']) or $data['title'] == ''){
            throw new Exception('Le titre est obligatoire.');
        }
        if(!isset($data['price']) or $data['price'] == ''){
            throw new Exception('Le prix est obligatoire.');
        }
        if($data['price'] < 0 or $data['price'] > 99999999.99){
            throw new Exception('Le prix doit être compris entre 0 et 99999999.99.');
        }
        $categoryId = filter_var($data['category_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if($categoryId === false){
            throw new Exception('Choisissez une catégorie valide.');
        }
        if(!$this->selectId('category', $categoryId)){
            throw new Exception('Catégorie introuvable.');
        }

        // Si le nombre de pages est vide, on enregistre NULL dans la base.
        $pageCount = null;
        if(isset($data['page_count']) && $data['page_count'] != ''){
            $pageCount = $data['page_count'];
            if($pageCount < 1 or $pageCount > 2147483647){
                throw new Exception('Le nombre de pages est incorrect.');
            }
        }
        return array(
            'title' => $data['title'],
            'description' => $data['description'],
            'page_count' => $pageCount,
            'price' => $data['price'],
            'edition' => $data['edition'],
            'age_group' => $data['age_group'],
            'category_id' => $categoryId
        );
    }

    public function insertBook(array $data):bool|int{
        $values = $this->bookData($data);
        if(!isset($data['author_1']) or $data['author_1'] == ''){
            throw new Exception("L'auteur principal est obligatoire.");
        }
        $authorIds = array();
        $fields = array('author_1', 'author_2', 'author_3');
        foreach($fields as $field){
            if(isset($data[$field]) && $data[$field] != ''){
                $name = $data[$field];
                // Réutilise l'auteur si son nom existe déjà, sinon le crée.
                $sql = "SELECT * FROM author WHERE name = :name";
                $stmt = $this->prepare($sql);
                $stmt->bindValue(':name', $name);
                $stmt->execute();
                if($stmt->rowCount() >= 1){
                    $author = $stmt->fetch();
                    $authorId = $author['id'];
                }else{
                    $authorId = $this->insert('author', array('name' => $name));
                    if(!$authorId){
                        throw new Exception("L'auteur n'a pas été enregistré.");
                    }
                }
                // Évite d'associer deux fois le même auteur au livre.
                $alreadySelected = false;
                foreach($authorIds as $selectedId){
                    if($selectedId == $authorId){
                        $alreadySelected = true;
                    }
                }
                if(!$alreadySelected){
                    $authorIds[] = $authorId;
                }
            }
        }
        $id = $this->insert($this->table, $values);
        if($id){
            foreach($authorIds as $authorId){
                $this->insert('book_author', array('book_id' => $id, 'author_id' => $authorId));
            }
        }
        return $id;
    }

    public function updateBook(array $data):bool{
        $id = $data['id'];
        if(!$this->find($id)){
            throw new Exception('Livre introuvable.');
        }
        $values = $this->bookData($data);
        if(!isset($data['authors']) or $data['authors'] == array()){
            throw new Exception('Choisissez au moins un auteur.');
        }
        $authorIds = array();
        foreach($data['authors'] as $authorId){
            if(!$this->selectId('author', $authorId)){
                throw new Exception('Auteur introuvable.');
            }
            foreach($authorIds as $selectedId){
                if($selectedId == $authorId){
                    throw new Exception('Choisissez des auteurs différents.');
                }
            }
            $authorIds[] = $authorId;
        }
        $values['id'] = $id;
        $update = $this->update($this->table, $values);
        if($update){
            // Remplace les anciennes associations par les auteurs cochés.
            $this->delete('book_author', $id, 'book_id');
            foreach($authorIds as $authorId){
                $this->insert('book_author', array('book_id' => $id, 'author_id' => $authorId));
            }
        }
        return $update;
    }

    public function remove(int $id):bool{
        // Supprime les liens avant le livre pour respecter les clés étrangères.
        $this->delete('book_author', $id, 'book_id');
        return $this->delete($this->table, $id);
    }
}
