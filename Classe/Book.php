<?php
require_once('Classe/CRUD.php');

class Book extends CRUD {
    protected string $table = 'book';

    // Récupère tous les livres en les classant par titre.
    public function all():array{
        return $this->select($this->table, 'title');
    }
    // Cherche un livre avec son id.
    public function find(int|string $id):array|bool{
        return $this->selectId($this->table, $id);
    }
    // Récupère seulement les auteurs associés à ce livre.
    public function authors(int $id):array{
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
    // Vérifie les informations du livre et prépare les données à enregistrer.
    private function bookData(array $data):array{
        $title = '';
        if(isset($data['title'])){
            $title = trim($data['title']);
        }
        if($title === ''){
            throw new Exception('Le titre est obligatoire.');
        }
        if(!isset($data['price']) or $data['price'] == ''){
            throw new Exception('Le prix est obligatoire.');
        }
        if(!is_numeric($data['price']) || $data['price'] < 0 || $data['price'] > 99999999.99){
            throw new Exception('Le prix doit être compris entre 0 et 99999999.99.');
        }
        $categoryId = null;
        if(isset($data['category_id'])){
            $categoryId = $data['category_id'];
        }
        $categoryId = filter_var($categoryId, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
        if($categoryId === false){
            throw new Exception('Choisissez une catégorie valide.');
        }
        if(!$this->selectId('category', $categoryId)){
            throw new Exception('Catégorie introuvable.');
        }

        // Si le nombre de pages est vide, on enregistre NULL dans la base.
        $pageCount = null;
        if(isset($data['page_count']) && $data['page_count'] != ''){
            $pageCount = filter_var($data['page_count'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 2147483647)));
            if($pageCount === false){
                throw new Exception('Le nombre de pages est incorrect.');
            }
        }
        return array(
            'title' => $title,
            'description' => $data['description'],
            'page_count' => $pageCount,
            'price' => $data['price'],
            'edition' => $data['edition'],
            'age_group' => $data['age_group'],
            'category_id' => $categoryId
        );
    }
    // Ajoute le livre et le relie aux auteurs choisis.
    public function insertBook(array $data):bool|int{
        $values = $this->bookData($data);
        if(empty($data['authors']) || !is_array($data['authors'])){
            throw new Exception('Choisissez au moins un auteur.');
        }
        $authorIds = array();
        foreach($data['authors'] as $value){
            $authorId = filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
            if($authorId === false || !$this->selectId('author', $authorId)){
                throw new Exception('Auteur introuvable.');
            }
            $authorIds[$authorId] = $authorId;
        }
        $id = $this->insert($this->table, $values);
        if(!$id){
            throw new Exception("Le livre n'a pas été enregistré.");
        }
        foreach($authorIds as $authorId){
            if(!$this->insert('book_author', array('book_id' => $id, 'author_id' => $authorId))){
                throw new Exception("L'auteur n'a pas été associé au livre.");
            }
        }
        return $id;
    }
    // Met à jour les informations du livre et les auteurs qui lui sont associés.
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
            if(!$this->delete('book_author', $id, 'book_id')){
                return false;
            }
            foreach($authorIds as $authorId){
                if(!$this->insert('book_author', array('book_id' => $id, 'author_id' => $authorId))){
                    return false;
                }
            }
        }
        return $update;
    }
    // Supprime le livre et ses auteurs s'ils n'ont plus aucun autre livre.
    public function remove(int $id):bool{
        $authors = $this->authors($id);
        // Supprime les liens avant le livre pour respecter les clés étrangères.
        if(!$this->delete('book_author', $id, 'book_id')){
            return false;
        }
        if(!$this->delete($this->table, $id)){
            return false;
        }
        // Garde les auteurs qui sont encore associés à un autre livre.
        $relations = $this->select('book_author', 'book_id');
        foreach($authors as $author){
            $hasBook = false;
            foreach($relations as $relation){
                if($relation['author_id'] == $author['id']){
                    $hasBook = true;
                }
            }
            if(!$hasBook){
                if(!$this->delete('author', $author['id'])){
                    return false;
                }
            }
        }
        return true;
    }
}
