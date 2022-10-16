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

    <section class="section post-details" id="post-details">
        <div class="container post-details__container">
            <div class="row post-details__row">
                <div class="col-12 post-details__col">
                    <div class="post-details__card">
                        <div class="post-details__card-header">
                            <h2 class="post-details__card-title"><?php echo $post->getTitle(); ?></h2>
                            <div class="post-details__card-subtitle">
                                <span class="post-details__card-subtitle-item">
                                    <i class="fas fa-user"></i>
                                    <?php echo $post->getAuthor()->getUsername(); ?>
                                </span>
                                <span class="post-details__card-subtitle-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?php echo $post->getCreatedAt(); ?>
                                </span>
                            </div>
                        </div>
                        <div class="post-details__card-body">
                            <img src="<?php echo $post->getHeroImageUrl(); ?>" alt="" class="post-details__card-img">
                            <p class="post-details__card-text">
                                <?php echo $post->getContent(); ?>
                            </p>
                        </div>
                        <?php
                        if (isset($_SESSION['user']) && unserialize($_SESSION['user'])->getId() === $post->getAuthor()->getId()) {
                            ?>
                            <div class="post-details__card-footer">
                                <a href="/post/<?php echo $post->getId(); ?>/edit" class="btn btn-primary">Edit</a>
                                <a href="/post/<?php echo $post->getId(); ?>/delete" class="btn btn-danger">Delete</a>
                            </div>
                            <?php
                        } elseif (isset($_SESSION['user']) && unserialize($_SESSION['user'])->getRole() === 'admin') {
                            ?>
                            <div class="post-details__card-footer">
                                <a href="/post/<?php echo $post->getId(); ?>/delete" class="btn btn-danger">Delete</a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php partial("footer"); ?>

</body>
</html>
