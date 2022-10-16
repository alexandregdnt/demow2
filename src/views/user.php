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

    <section class="section user-details" id="user-details">
        <h2>Profil de <?php echo $user->getUsername(); ?></h2>
        <div class="user-details__content">
            <div class="user-details__content__left">
                <div class="user-details__content__left__avatar">
                    <img src="https://www.gravatar.com/avatar/<?php echo md5($user->getEmail()); ?>?s=200" alt="Avatar de <?php echo $user->getUsername(); ?>">
                </div>
                <div class="user-details__content__left__bio">
                    <p><?php if (!empty($user->getBio())) echo $user->getBio(); ?></p>
                <?php
                if (isset($_SESSION['user']) && unserialize($_SESSION['user'])->getId() === $user->getId()) {
                    ?>
                        <div class="user-details__content__left__actions">
                            <a href="/user/<?= $user->getId() ?>/edit" class="btn btn-primary">Modifier mon profil</a>
                            <a href="/user/<?= $user->getId() ?>/delete" class="btn btn-primary">Supprimer mon compte</a>
                        </div>
                    <?php
                } elseif (isset($_SESSION['user']) && unserialize($_SESSION['user'])->getRole() === 'admin') {
                    ?>
                        <div class="user-details__content__left__actions">
                            <a href="/user/<?= $user->getId() ?>/delete" class="btn btn-primary">Supprimer le compte</a>
                        </div>
                    <?php
                }
                ?>
            </div>
            <div class="user-details__content__right">
                <div class="user-details__content__right__info">
                    <h3>Informations</h3>
                    <ul>
                        <li><strong>Nom d'utilisateur : </strong><?php echo $user->getUsername(); ?></li>
                        <li><strong>Email : </strong><?php echo $user->getEmail(); ?></li>
                        <li><strong>Date de création : </strong><?php echo $user->getCreatedAt(); ?></li>
                    </ul>
                </div>
                <div class="user-details__content__right__posts">
                    <h3>Articles</h3>
                    <ul>
                        <?php
                        if (!empty($user_posts)) {
                            foreach ($user_posts as $post) {
                                ?>
                                <li><a href="/post/<?php echo $post->getId(); ?>"><?php echo $post->getTitle(); ?></a></li>
                                <?php
                            }
                        } else {
                            ?>
                            <li>Cet utilisateur n'a pas encore écrit d'article.</li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

</main>

<?php partial("footer"); ?>

</body>
</html>
