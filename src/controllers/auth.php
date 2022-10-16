<?php
require_once ("../classes/User.php");

$action = getString('action');

if (isLogged() && (!isset($action) || $action != 'logout')) redirect('/');

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
            redirect('/');
            break;
    }
}

view("auth");

function isLogged(): bool
{
    return isset($_SESSION['user']) && unserialize($_SESSION['user'])->getId() != null;
}

function login(): void
{
    $authenticationMethod = postString('authentication-method');
    $password = postString('password');

    if (isset($authenticationMethod) && !empty($authenticationMethod) && isset($password) && !empty($password)) {
        if (valid_email($authenticationMethod)) {
            $user = User::getByEmail($authenticationMethod);
        } else if (valid_phone($authenticationMethod)) {
            $user = User::getByPhone($authenticationMethod);
        } else {
            $user = User::getByUsername($authenticationMethod);
        }

        if ($user && $user->getId() != null) {
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

function register(): void
{
    $username = postString('username') ? strtolower(postString('username')) : null;
    $password = postString('password');
    $passwordConfirm = postString('password_confirm');
    $email = postString('email') ? strtolower(postString('email')) : null;
    $phone = postString('phone');
    $firstname = postString('firstname') ? capitalize(postString('firstname')) : null;
    $lastname = postString('lastname') ? capitalize(postString('lastname')) : null;
    $dateOfBirth = postString('date_of_birth');

    if (isset($username) && !empty($username) && isset($password) && !empty($password) && isset($passwordConfirm) && !empty($passwordConfirm) && isset($email) && !empty($email) && isset($firstname) && !empty($firstname) && isset($lastname) && !empty($lastname)) {
        if (valid_name($firstname) && valid_name($lastname)) {
            if (empty($dateOfBirth) || valid_date($dateOfBirth)) {
                if (valid_password($password)) {
                    if ($password == $passwordConfirm) {
                        if (valid_username($username)) {
                            $user = User::getByUsername($username);

                            if (!$user || $user->getId() == null) {
                                if (valid_email($email)) {
                                    $user = User::getByEmail($email);

                                    if (!$user || $user->getId() == null) {
                                        if (empty($phone) || valid_phone($phone)) {
                                            $user = User::getByPhone($phone);

                                            if (!$user || $user->getId() == null) {
                                                $user = new User();
                                                $user->setUsername($username);
                                                $user->setEmail($email);
                                                $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
                                                $user->setFirstname($firstname);
                                                $user->setLastname($lastname);
                                                if (!empty($phone)) $user->setPhone($phone);
                                                if (!empty($dateOfBirth)) $user->setDateOfBirth(date('Y-m-d', strtotime($dateOfBirth)));
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
