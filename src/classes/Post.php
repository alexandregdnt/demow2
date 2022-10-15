<?php

class Post {
    private $id;
    private $author;
    private $title;
    private $content;
    private $hero_image_url;
    private $created_at;
    private $updated_at;

    # CONSTRUCTOR
    public function __construct()
    {
        $this->id = -1;
        $this->author = "";
        $this->title = "";
        $this->content = "";
        $this->hero_image_url = "";
        $this->created_at = "";
        $this->updated_at = "";
    }

    # GETTERS
    public function getId() {
        return $this->id;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }

    public function getHeroImageUrl() {
        return $this->hero_image_url;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }

    # SETTERS
    public function setId($id) {
        $this->id = $id;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setHeroImageUrl($hero_image_url) {
        $this->hero_image_url = $hero_image_url;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt($updated_at) {
        $this->updated_at = $updated_at;
    }

    # DATABASE METHODS
    public function get($id) {
        $db = bdd_connection();
        $request = $db->prepare("SELECT * FROM posts WHERE post_id LIKE ?");
        $request->execute([$id]);
        $result = $request->fetch();

        if ($result->rowCount() > 0) {
            $this->id = $result['id'];
            $this->author = $result['author'];
            $this->title = $result['title'];
            $this->content = $result['content'];
            $this->hero_image_url = $result['hero_image_url'];
            $this->created_at = $result['created_at'];
            $this->updated_at = $result['updated_at'];
        }
    }
    public function create() {
        $db = bdd_connection();
        $request = $db->prepare("
            INSERT INTO posts (author, title, content, hero_image_url, updated_at)
            VALUES (?, ?, ?, ?, ?, ?);");
        $request->execute(array(
            $this->author,
            $this->title,
            $this->content,
            $this->hero_image_url,
            $this->updated_at
        ));
        $this->id = $db->lastInsertId();
    }
    public function update() {
        $db = bdd_connection();
        $request = $db->prepare("
            UPDATE posts
            SET author = ?, title = ?, content = ?, hero_image_url = ?, updated_at = ?
            WHERE post_id = ?;");
        $request->execute(array(
            $this->author,
            $this->title,
            $this->content,
            $this->hero_image_url,
            $this->updated_at,
            $this->id
        ));
    }
    public function save() {
        if ($this->id == -1) {
            $this->create();
        } else {
            $this->setUpdatedAt(date("Y-m-d H:i:s"));
            $this->update();
        }
    }
}
