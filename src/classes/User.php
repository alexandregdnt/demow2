<?php

class User {
    private $id;
    private $username;
    private $email;
    private $phone;
    private $role;
    private $password;
    private $firstname;
    private $lastname;
    private $date_of_birth;
    private $avatar_url;
    private $created_at;
    private $updated_at;

    # CONSTRACTOR
    public function __construct()
    {
        $this->id = null;
        $this->username = "";
        $this->email = "";
        $this->phone = "";
        $this->role = "user";
        $this->password = "";
        $this->firstname = "";
        $this->lastname = "";
        $this->date_of_birth = "";
        $this->avatar_url = "";
        $this->created_at = null;
        $this->updated_at = null;
    }

    public static function copy(User $user): User
    {
        $instance = new self();
        $instance->id = $user->getId();
        $instance->username = $user->getUsername();
        $instance->email = $user->getEmail();
        $instance->phone = $user->getPhone();
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
    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getRole() {
        return $this->role;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function getDateOfBirth() {
        return $this->date_of_birth;
    }

    public function getAvatarUrl() {
        return $this->avatar_url;
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

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function setDateOfBirth($date_of_birth) {
        $this->date_of_birth = $date_of_birth;
    }

    public function setAvatarUrl($avatar_url) {
        $this->avatar_url = $avatar_url;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt($updated_at) {
        $this->updated_at = $updated_at;
    }

    # DATABASE METHODS
    private function push($db_result) {
        $this->id = $db_result['user_id'];
        $this->username = $db_result['username'];
        $this->email = $db_result['email'];
        $this->phone = $db_result['phone'];
        $this->role = $db_result['role'];
        $this->password = $db_result['password'];
        $this->firstname = $db_result['firstname'];
        $this->lastname = $db_result['lastname'];
        $this->date_of_birth = $db_result['date_of_birth'];
        $this->avatar_url = $db_result['avatar_url'];
        $this->created_at = $db_result['created_at'];
        $this->updated_at = $db_result['updated_at'];
    }

    public function get($id): bool
    {
        $db = bdd_connection();
        $statement = $db->prepare("SELECT * FROM users WHERE user_id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->push($result);
            return true;
        } else {
            return false;
        }
    }
    public function getByAuthenticationMethod($value, $method = null): bool
    {
        if (empty($method)) $method = preg_match("/@/", $value) ? "email" : (preg_match("/^0[0-9]{9}$/", $value) ? "phone" : "username");
        $db = bdd_connection();

        switch ($method) {
            case "email":
                $statement = $db->prepare("SELECT * FROM users WHERE email = :value");
                break;
            case "phone":
                $statement = $db->prepare("SELECT * FROM users WHERE phone = :value");
                break;
            case "username":
                $statement = $db->prepare("SELECT * FROM users WHERE username = :value");
                break;
            default:
                return false;
        }
        $statement->bindValue(":value", $value);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->push($result);
            return true;
        } else {
            return false;
        }
    }


    public function create() {
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
    public function update() {
        $db = bdd_connection();
        $request = $db->prepare("
            UPDATE users
            SET email = ?, username = ?, phone = ?, password = ?, firstname = ?, lastname = ?, date_of_birth = ?, updated_at = ?
            WHERE user_id = ?;");

        $request->execute(array(
            $this->email,
            $this->username,
            $this->phone,
            $this->password,
            $this->firstname,
            $this->lastname,
            $this->date_of_birth,
            $this->updated_at,
            $this->id
        ));
    }
    public function save() {
        if ($this->id == null) {
            $this->create();
        } else {
            $this->setUpdatedAt(date("Y-m-d H:i:s"));
            $this->update();
        }
    }
}
