<!DOCTYPE html>
<html dir="ltr" lang="fr">
<?php partial("head"); ?>
<body>

<?php partial("header"); ?>

<main class="main">
    <h1>Blog</h1>

    <?php
    if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
        ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['error']; ?>
        </div>
        <?php
        unset($_SESSION['error']);
    }
    ?>

    <section class="section post-form" id="post-form">
        <div class="container post-form__container">
            <div class="row post-form__row">
                <div class="col-12 post-form__col">
                    <div class="post-form__card">
                        <div class="post-form__card-header">
                            <h2 class="post-form__card-title"><?= $action === 'create' ? 'Create a new post' : 'Edit an existing post'; ?></h2>
                        </div>
                        <div class="post-form__card-body">
                            <form action="<?= isset($post) ? '/post/'. $post->getId() .'/edit' : '/post/create'; ?>" method="post">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" value="<?= isset($post) ? $post->getTitle() : '' ?>">
                                </div>
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea name="content" id="content" class="form-control"><?= isset($post) ? $post->getContent() : '' ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="hero_image_url">Hero image URL</label>
                                    <input type="url" name="hero_image_url" id="hero_image_url" class="form-control" value="<?= isset($post) ? $post->getHeroImageUrl() : '' ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php partial("footer"); ?>

</body>
</html>
