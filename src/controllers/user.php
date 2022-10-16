<?php
require_once ('../classes/User.php');
require_once ('../classes/Post.php');

if (!isset($_SESSION['user']) || unserialize($_SESSION['user'])->getId() == null) {
    redirect('/auth');
}

$action = getString('action');
$userId = getInt('id');

if (isset($action) && !empty($action)) {
    switch ($action) {
        case 'view':
            view_user($userId);
            break;
        case 'edit':
            edit_user($userId);
            break;
        case 'delete':
            delete_user($userId);
            break;
        default:
            redirect('/');
            break;
    }
}

function view_user($id): void
{
    if (isset($id) && !empty($id)) {
        $user = User::getById($id);
    } else {
        $user = unserialize($_SESSION['user']);
    }

    if ($user && $user->getId() != null) {
        $user_posts = Post::getByAuthorId($id);
        view('user', ['user' => $user, 'user_posts' => $user_posts]);
    } else {
        redirect('/');
    }
}

function edit_user($id): void
{
    $user = User::getById($id);

    if ($user && $user->getId() != null) {
        if ($user->getId() == unserialize($_SESSION['user'])->getId()) {
            if (isset($_POST['submit'])) {
                $newUser = $user;

                $username = postString('username') ? strtolower(postString('username')) : null;
                $password = postString('password');
                $passwordConfirm = postString('password_confirm');
                $email = postString('email') ? strtolower(postString('email')) : null;
                $phone = postString('phone');
                $bio = postString('bio');
                $avatar_url = postString('avatar_url');

                if (isset($username) && !empty($username) && valid_username($username)) {
                    $existingUser = User::getByUsername($username);

                    if (!$existingUser || $existingUser->getId() == $user->getId()) {
                        $newUser->setUsername($username);
                    } else {
                        $_SESSION['error'] = 'Username already taken';
                        redirect('/user');
                    }
                } else {
                    $_SESSION['error'] = 'Invalid username';
                    redirect('/user');
                }

                if (isset($email) && !empty($email) && valid_email($email)) {
                    $existingUser = User::getByEmail($email);

                    if (!$existingUser || $existingUser->getId() == $user->getId()) {
                        $newUser->setEmail($email);
                    } else {
                        $_SESSION['error'] = 'Email already taken';
                        redirect('/user');
                    }
                } else {
                    $_SESSION['error'] = 'Invalid email';
                    redirect('/user');
                }

                if (isset($phone) && !empty($phone) && valid_phone($phone)) {
                    $existingUser = User::getByPhone($phone);

                    if (!$existingUser || $existingUser->getId() == $user->getId()) {
                        $newUser->setPhone($phone);
                    } else {
                        $_SESSION['error'] = 'Phone already taken';
                        redirect('/user');
                    }
                } elseif (empty($phone)) {
                    $newUser->setPhone(null);
                } else {
                    $_SESSION['error'] = 'Invalid phone';
                    redirect('/user');
                }

                if (isset($bio) && !empty($bio)) {
                    $newUser->setBio($bio);
                } else {
                    $newUser->setBio(null);
                }

                if (isset($avatar_url) && !empty($avatar_url) && valid_url($avatar_url)) {
                    $newUser->setAvatarUrl($avatar_url);
                } elseif (empty($avatar_url)) {
                    $newUser->setAvatarUrl(null);
                } else {
                    $_SESSION['error'] = 'Invalid avatar url';
                    redirect('/user');
                }

                if (isset($password) && !empty($password) && isset($passwordConfirm) && !empty($passwordConfirm)) {
                    if ($password == $passwordConfirm) {
                        if (valid_password($password)) {
                            $newUser->setPassword($password);
                        } else {
                            $_SESSION['error'] = 'Invalid password';
                            redirect('/user');
                        }
                    } else {
                        $_SESSION['error'] = 'Passwords do not match';
                        redirect('/user');
                    }
                }

                $newUser->update();
                $_SESSION['user'] = serialize($newUser);
                $_SESSION['success'] = 'User updated successfully';
                redirect('/user');

            } else {
                view('user-form', ['user' => $user]);
            }
        } else {
            $_SESSION['error'] = 'You do not have permission to edit this user';
            redirect('/');
        }
    } else {
        $_SESSION['error'] = 'User not found';
        redirect('/');
    }
}

function delete_user($id): void
{
    $user = User::getById($id);

    if ($user && $user->getId() != null) {
        if ($user->getId() == unserialize($_SESSION['user'])->getId()) {
            $user->delete();

            $_SESSION['success'] = 'User deleted successfully';
            redirect('/auth/logout');
        } elseif (unserialize($_SESSION['user'])->getRole() == 'admin') {
            $user->delete();

            $_SESSION['success'] = 'User deleted successfully';
            redirect('/');
        } else {
            $_SESSION['error'] = 'You do not have permission to delete this user';
            redirect('/');
        }
    } else {
        redirect('/');
    }
}
