<?php
require_once ("../classes/User.php");

if (isset($_SESSION['user']) && unserialize($_SESSION['user'])->getId() != null) {
    redirect('/');
} else {
    session_destroy();
    $_SESSION = [];
    redirect('/auth');
}

$action = getString('action');

if (isset($action) && !empty($action)) {
    switch ($action) {
        case 'login':
            login();
            break;
        case 'logout':
            session_destroy();
            $_SESSION = [];
            redirect('/');
            break;
        case 'register':
            register();
            break;
        default:
            controller('error');
            exit();
            break;
    }
}

function login()
{
    $authenticationMethod = postString('authentication-method');
    $password = postString('password');

    if (isset($authenticationMethod) && !empty($authenticationMethod) && isset($password) && !empty($password)) {
        $user = new User();
        $exist = $user->getByAuthenticationMethod($authenticationMethod);

        if ($exist && $user->getId() != null) {
            if (password_verify($password, $user->getPassword())) {
                $_SESSION['user'] = serialize($user);
                redirect('/');
            } else {
                $_SESSION['error'] = 'Wrong password';
                redirect('/auth');
            }
        } else {
            $_SESSION['error'] = 'User not found';
            redirect('/auth');
        }
    } else {
        $_SESSION['error'] = 'Missing fields';
        redirect('/auth');
    }
}

function register()
{
    $username = postString('username');
    $password = postString('password');
    $passwordConfirm = postString('password_confirm');
    $email = postString('email');
    $phone = postString('phone');
    $firstname = postString('firstname');
    $lastname = postString('lastname');
    $dateOfBirth = postString('date_of_birth');

    if (isset($username) && !empty($username) && isset($password) && !empty($password) && isset($passwordConfirm) && !empty($passwordConfirm) && isset($email) && !empty($email) && isset($phone) && !empty($phone) && isset($firstname) && !empty($firstname) && isset($lastname) && !empty($lastname) && isset($dateOfBirth) && !empty($dateOfBirth)) {
        if (preg_match('/^\w{2,24}$/', $firstname) && preg_match('/^\w{2,24}$/', $lastname)) {
            if (preg_match('/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/', $dateOfBirth)) {
                if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
                    if ($password == $passwordConfirm) {
                        $user = new User();

                        if (preg_match('/^\w{4,24}$/', $username)) {
                            $exist = $user->getByAuthenticationMethod($username, 'username');

                            if (!$exist) {
                                if (preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $email)) {
                                    $exist = $user->getByAuthenticationMethod($email, 'email');

                                    if (!$exist) {
                                        if (preg_match('/^(\+33|0)[1-9](\d{2}){4}$/', $phone)) {
                                            $exist = $user->getByAuthenticationMethod($phone, 'phone');

                                            if (!$exist) {
                                                $user->setUsername($username);
                                                $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
                                                $user->setEmail($email);
                                                $user->setPhone($phone);
                                                $user->setFirstname($firstname);
                                                $user->setLastname($lastname);
                                                $user->setDateOfBirth($dateOfBirth);
                                                $user->setRole('user');
                                                $user->setAvatarUrl('https://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?s=200&d=mp');
                                                $user->setCreatedAt(date('Y-m-d H:i:s'));
                                                $user->setUpdatedAt(date('Y-m-d H:i:s'));
                                                $user->save();

                                                $_SESSION['user'] = serialize($user);
                                                redirect('/');
                                            } else {
                                                $_SESSION['error'] = 'Phone already used';
                                                redirect('/auth');
                                            }
                                        } else {
                                            $_SESSION['error'] = 'Invalid phone number';
                                            redirect('/auth');
                                        }
                                    } else {
                                        $_SESSION['error'] = 'Email already used';
                                        redirect('/auth');
                                    }
                                } else {
                                    $_SESSION['error'] = 'Invalid email';
                                    redirect('/auth');
                                }
                            } else {
                                $_SESSION['error'] = 'Username already exist';
                                redirect('/auth');
                            }
                        } else {
                            $_SESSION['error'] = 'Username must be between 4 and 24 characters';
                            redirect('/auth');
                        }
                    } else {
                        $_SESSION['error'] = 'Passwords do not match';
                        redirect('/auth');
                    }
                } else {
                    $_SESSION['error'] = 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number and one special character';
                    redirect('/auth');
                }
            } else {
                $_SESSION['error'] = 'Invalid date of birth';
                redirect('/auth');
            }
        } else {
            $_SESSION['error'] = 'Name must be between 2 and 24 characters';
            redirect('/auth');
        }
    } else {
        $_SESSION['error'] = 'Informations missing';
    }
}

view("auth");
