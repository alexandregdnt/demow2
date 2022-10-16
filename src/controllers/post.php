<?php
require_once ('../classes/User.php');
require_once ('../classes/Post.php');

if (!isset($_SESSION['user']) || unserialize($_SESSION['user'])->getId() == null) {
    redirect('/auth');
}

$action = getString('action');
$postId = getInt('id');

if (isset($action) && !empty($action)) {
    switch ($action) {
        case 'create':
            create_post();
            break;
        case 'view':
            view_post($postId);
            break;
        case 'edit':
            edit_post($postId);
            break;
        case 'delete':
            delete_post($postId);
            break;
        default:
            redirect('/');
            break;
    }
}


function create_post(): void
{
    $title = postString('title');
    $content = postString('content');
    $heroImageUrl = postString('hero-image-url');

    if (isset($title) && !empty($title) && isset($content) && !empty($content)) {
        $post = new Post();
        $post->setTitle($title);
        $post->setContent($content);
        $post->setHeroImageUrl($heroImageUrl);
        $post->setAuthorId(unserialize($_SESSION['user'])->getId());
        $post->setAuthor(unserialize($_SESSION['user']));
        $post->save();

        $_SESSION['success'] = 'Post created successfully';
        redirect('/');
    } else {
        view('post-form', ['action' => 'create']);
    }
}

function view_post($id): void
{
    $post = Post::getById($id);

    if ($post && $post->getId() != null) {
        view('post', ['post' => $post]);
    } else {
        $_SESSION['error'] = 'Post not found';
        redirect('/');
    }
}

function edit_post($id): void
{
    if (isset($id) && !empty($id)) {
        $post = Post::getById($id);
        if ($post && $post->getId() != null) {
            if ($post->getAuthorId() == unserialize($_SESSION['user'])->getId()) {
                $newTitle = postString('title');
                $newContent = postString('content');
                $newHeroImageUrl = postString('hero_image_url');

                if (isset($newTitle) && !empty($newTitle) && isset($newContent) && !empty($newContent)) {
                    $post->setTitle($newTitle);
                    $post->setContent($newContent);

                    if (isset($newHeroImageUrl) && !empty($newHeroImageUrl) && valid_url($newHeroImageUrl)) {
                        $post->setHeroImageUrl($newHeroImageUrl);
                    } elseif (empty($newHeroImageUrl)) {
                        $post->setHeroImageUrl(null);
                    } else {
                        $_SESSION['error'] = 'Invalid hero image url';
                        redirect('/post/' . $id);
                    }

                    $post->save();
                    $_SESSION['success'] = 'Post edited successfully';
                    redirect('/');
                } else {
                    view('post-form', ['action' => 'edit', 'post' => $post]);
                }
            } else {
                $_SESSION['error'] = 'You are not the author of this post';
                redirect('/post/'. $id);
            }
        } else {
            $_SESSION['error'] = 'Post not found';
            redirect('/');
        }
    } else {
        $_SESSION['error'] = 'Post not found';
        redirect('/');
    }
}

function delete_post($id): void
{
    if (isset($id) && !empty($id)) {
        $post = Post::getById($id);
        if ($post && $post->getId() != null) {
            if ($post->getAuthorId() == unserialize($_SESSION['user'])->getId() || unserialize($_SESSION['user'])->getRole() == 'admin') {
                $post->delete();
                $_SESSION['success'] = 'Post deleted successfully';
                redirect('/');
            } else {
                $_SESSION['error'] = 'You are not the author of this post';
                redirect('/post/'. $id);
            }
        } else {
            $_SESSION['error'] = 'Post not found';
            redirect('/');
        }
    } else {
        $_SESSION['error'] = 'Post not found';
        redirect('/');
    }
}
