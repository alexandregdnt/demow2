<?php

class User {
    private int | null $id = null;
    private string $username = '';
    private string $email = '';
    private string | null $phone = null;
    private string | null $bio = null;
    private string $role = 'user';
    private string $password = '';
    private string $firstname = '';
    private string $lastname = '';
    private string | null $date_of_birth = null;
    private string | null $avatar_url = null;
    private string $created_at;
    private string $updated_at;

    # CONSTRUCTORS
    public function __construct()
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
    }
    public function __copy(User $user): User
    {
        $instance = new self();
        $instance->id = $user->getId();
        $instance->username = $user->getUsername();
        $instance->email = $user->getEmail();
        $instance->phone = $user->getPhone();
        $instance->bio = $user->getBio();
        $instance->role = $user->getRole();
        $instance->password = $user->getPassword();
        $instance->firstname = $user->getFirstname();
        $instance->lastname = $user->getLastname();
        $instance->date_of_birth = $user->getDateOfBirth();
        $instance->avatar_url = $user->getAvatarUrl();
        $instance->created_at = $user->getCreatedAt();
        $instance->updated_at = $user->getUpdatedAt();
        return $instance;
    }

    # GETTERS
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getDateOfBirth(): ?string
    {
        return $this->date_of_birth;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    # SETTERS
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    public function setBio($bio): void
    {
        $this->bio = $bio;
    }

    public function setRole($role): void
    {
        $this->role = $role;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    public function setDateOfBirth($date_of_birth): void
    {
        $this->date_of_birth = $date_of_birth;
    }

    public function setAvatarUrl($avatar_url): void
    {
        $this->avatar_url = $avatar_url;
    }

    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    # DATABASE METHODS
    public static function getById(int $id): User | bool
    {
        $db = bdd_connection();
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(":id", $id);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_CLASS, "User");
        return $statement->fetch();
    }
    public static function getByEmail(string $email): User | bool
    {
        $db = bdd_connection();
        $query = "SELECT * FROM users WHERE email = :email";
        $statement = $db->prepare($query);
        $statement->bindValue(":email", $email);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_CLASS, "User");
        return $statement->fetch();
    }
    public static function getByUsername(string $username): User | bool
    {
        $db = bdd_connection();
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $db->prepare($query);
        $statement->bindValue(":username", $username);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_CLASS, "User");
        return $statement->fetch();
    }
    public static function getByPhone(string $phone): User | bool
    {
        $db = bdd_connection();
        $query = "SELECT * FROM users WHERE phone = :phone";
        $statement = $db->prepare($query);
        $statement->bindValue(":phone", $phone);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_CLASS, "User");
        return $statement->fetch();
    }
    public static function getAll(): array
    {
        $db = bdd_connection();
        $query = "SELECT * FROM users";
        $statement = $db->prepare($query);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_CLASS, "User");
        return $statement->fetchAll();
    }

    public function create(): void
    {
        $db = bdd_connection();
        $request = $db->prepare("
            INSERT INTO users (email, username, phone, password, firstname, lastname, date_of_birth, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");

        $request->execute(array(
            $this->email,
            $this->username,
            $this->phone,
            $this->password,
            $this->firstname,
            $this->lastname,
            $this->date_of_birth,
            $this->created_at,
            $this->updated_at
        ));

        $this->id = $db->lastInsertId();
    }
    public function update(): void
    {
        $db = bdd_connection();
        $request = $db->prepare("
            UPDATE users
            SET email = ?, username = ?, phone = ?, bio = ?, password = ?, firstname = ?, lastname = ?, date_of_birth = ?, updated_at = ?
            WHERE id = ?;");

        $request->execute(array(
            $this->email,
            $this->username,
            $this->phone,
            $this->bio,
            $this->password,
            $this->firstname,
            $this->lastname,
            $this->date_of_birth,
            $this->updated_at,
            $this->id
        ));
    }
    public function delete(): void
    {
        $db = bdd_connection();
        $request = $db->prepare("
            DELETE FROM users
            WHERE id = ?;");

        $request->execute(array(
            $this->id
        ));
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
