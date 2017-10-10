<?php

include_once "Category.php";
include_once "Post.php";
include_once "DbConnect.php";

class Repository
{
    private $connect;
    public function __construct()
    {
        $this->connect = new DbConnect();
    }

    public function GetAllPosts(){
        $stmt = $this->connect->GetDb()->prepare("SELECT post.id, post.title, description, login, category_id, date, picture,
                                            category.title AS category
                                            FROM post, category WHERE category_id = category.id ORDER BY date DESC ");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Post');
        return $stmt->fetchAll();
    }

    public function GetPostsByCategoryId($category_id){
        $stmt = $this->connect->GetDb()->prepare("SELECT post.id, post.title, description, login, category_id, date, picture,
                                            category.title AS category
                                            FROM post, category WHERE category_id = :category_id AND category_id = category.id
                                            ORDER BY date DESC ");
        $stmt->execute(array('category_id'=>$category_id));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Post');
        return $stmt->fetchAll();
    }

    public function GetPostById($id){
        $stmt = $this->connect->GetDb()->prepare("SELECT post.id, post.title, description, login, category_id, date, picture,
                                            category.title AS category
                                            FROM post, category WHERE post.id = :post_id AND category_id = category.id LIMIT 1");
        $stmt->execute(array(':post_id' => $id));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Post');
        return $stmt->fetch();
    }

    public function GetAllCategories(){
        $stmt = $this->connect->GetDb()->prepare("SELECT * FROM category");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Category');
        return $stmt->fetchAll();
    }

    public function GetCategoryById($id){
        $stmt = $this->connect->GetDb()->prepare("SELECT * FROM category WHERE id = :id LIMIT 1");
        $stmt->execute(array(':id' => $id));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Category');
        return $stmt->fetch();
    }

    public function AddNewPost(&$post)
    {
        try{
            $post->id = 0;
            $post->login = Credentials::getLogin();
            $today = new DateTime();
            $post->date = $today->format('Y-m-d H:i:s');;

            $stmt = $this->connect->GetDb()->prepare("INSERT INTO post VALUES (:id, :title, :description, :login, :picture, :date, :category_id)");
            $stmt->execute(array(
                ':id' => $post->id,
                ':title' => $post->title,
                ':description' => $post->description,
                ':login'=>$post->login,
                ':picture'=>$post->picture,
                ':date'=>$post->date,
                ':category_id'=>$post->category_id));
            $id = $this->connect->GetDb()->lastInsertId();
            $post->id = $id;

            return $this->GetPostById($id);
        }
        catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }

    public function SearchPostsByWordsInTitle($string){
        $search = "'".$string."'";
        $stmt = $this->connect->GetDb()->prepare("SELECT post.id, post.title, description, login, category_id, date, picture,
                                            category.title AS category, MATCH (post.title) AGAINST (:search) AS score
                                            FROM post, category WHERE MATCH (post.title) AGAINST (:search) AND category_id = category.id;");
        $stmt->execute(array('search'=>$search));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Post');
        return $stmt->fetchAll();
    }
}

