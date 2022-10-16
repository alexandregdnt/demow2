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

    <section class="section user-update-form" id="user-update-form">
        <h2>Modifier mon profil</h2>
        <form action="/user/<?php echo $user->getId(); ?>/edit" method="post">
            <div class="form-group">
                <label for="avatar_url">Avatar Url</label>
                <input type="url" class="form-control" id="avatar_url" name="avatar_url" value="<?php if (!empty($user->getAvatarUrl())) echo $user->getAvatarUrl(); ?>">
            </div>
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea name="bio" id="bio" cols="30" rows="10"><?php if (!empty($user->getBio())) echo $user->getBio(); ?></textarea>
            </div>
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user->getUsername(); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user->getEmail(); ?>">
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?php if (!empty($user->getPhone())) echo $user->getPhone(); ?>">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="password_confirm">Confirmation du mot de passe</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm">
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Modifier mon profil</button>
        </form>
    </section>

</main>

<?php partial("footer"); ?>

</body>
</html>
