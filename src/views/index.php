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

    <section class="section posts" id="posts">
        <div class="container posts__container">
            <?php
            foreach ($posts as $post) {
                ?>
                <div class="posts__content">
                    <h2 class="posts__title"><?php echo $post->getTitle(); ?></h2>
                    <p class="posts__text"><?php echo $post->getContent(); ?></p>
                    <a href="/user/<?php echo $post->getAuthor()->getId(); ?>" class="posts__author">By <?php echo $post->getAuthor()->getUsername(); ?></a>
                    <a href="/post/<?php echo $post->getId(); ?>" class="posts__link">Read more</a>
                    <?php
                    if (unserialize($_SESSION['user'])->getId() == $post->getAuthorId()) {
                        ?>
                        <a href="/post/<?php echo $post->getId(); ?>/edit" class="posts__link">Edit</a>
                        <a href="/post/<?php echo $post->getId(); ?>/delete" class="posts__link">Delete</a>
                        <?php
                    } elseif (unserialize($_SESSION['user'])->getRole() == 'admin') {
                        ?>
                        <a href="/post/<?php echo $post->getId(); ?>/delete" class="posts__link">Delete</a>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
    </section>

</main>

<?php partial("footer"); ?>

</body>
</html>
