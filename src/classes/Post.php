<?php

class Post {
    private int | null $id = null;
    private int | null $author_id = null;
    private string $title = '';
    private string $content = '';
    private string | null $hero_image_url = null;
    private string $created_at;
    private string | null $updated_at = null;
    private User $author;

    # CONSTRUCTOR
    public function __construct()
    {
        if ($this->author_id != null) {
            $this->author = User::getById($this->author_id);
        } else {
            $this->author = new User();
        }

        $this->created_at = date('Y-m-d H:i:s');
    }

    # GETTERS
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getHeroImageUrl(): ?string
    {
        return $this->hero_image_url;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    # SETTERS
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setAuthorId($author_id): void
    {
        $this->author_id = $author_id;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function setHeroImageUrl($hero_image_url): void
    {
        $this->hero_image_url = $hero_image_url;
    }

    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    # DATABASE METHODS
    public static function getById($id): Post | bool
    {
        $db = bdd_connection();
        $query = "SELECT * FROM posts WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(":id", $id);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_CLASS, "Post");
        return $statement->fetch();
    }
    public static function getByAuthorId($author_id): array
    {
        $db = bdd_connection();
        $query = "SELECT * FROM posts WHERE author_id = :author_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":author_id", $author_id);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_CLASS, "Post");
        return $statement->fetchAll();
    }
    public static function getAll(): array
    {
        $db = bdd_connection();
        $query = "SELECT * FROM posts";
        $statement = $db->prepare($query);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_CLASS, "Post");
        return $statement->fetchAll();
    }

    public function create(): void
    {
        $db = bdd_connection();
        $request = $db->prepare("
            INSERT INTO posts (author_id, title, content, hero_image_url, created_at)
            VALUES (?, ?, ?, ?, ?);");
        $request->execute(array(
            $this->author->getId(),
            $this->title,
            $this->content,
            $this->hero_image_url,
            $this->created_at
        ));
        $this->id = $db->lastInsertId();
    }
    public function update(): void
    {
        $db = bdd_connection();
        $request = $db->prepare("
            UPDATE posts
            SET title = ?, content = ?, hero_image_url = ?, updated_at = ?
            WHERE id = ?;");
        $request->execute(array(
            $this->title,
            $this->content,
            $this->hero_image_url,
            $this->updated_at,
            $this->id
        ));
    }
    public function delete(): void
    {
        $db = bdd_connection();
        $request = $db->prepare("
            DELETE FROM posts
            WHERE id = ?;");
        $request->execute(array($this->id));
    }
    public function save(): void
    {
        if ($this->id == null) {
            $this->create();
        } else {
            $this->setUpdatedAt(date("Y-m-d H:i:s"));
            $this->update();
        }
    }
}
