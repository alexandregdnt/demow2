<!DOCTYPE html>
<html dir="ltr" lang="fr">
<?php partial("head"); ?>
<body>

<?php partial("header"); ?>

<main class="main">
    <h1>Authentication</h1>

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

    <section class="section forms" id="forms">
            <div class="container forms__container">
                <div class="forms__content">
                    <h2 class="forms__title">Login</h2>
                    <form action="/auth/login" method="POST" class="forms__form">
                        <div class="forms__input-group">
                            <input type="text" name="authentication-method" placeholder="Username, Email, Phone" class="forms__input" required>
                        </div>
                        <div class="forms__input-group">
                            <input type="password" name="password" placeholder="Password" class="forms__input" required>
                        </div>
                        <button type="submit" class="forms__button">Login</button>
                    </form>
                </div>
                <div class="forms__content">
                    <h2 class="forms__title">Register</h2>
                    <form action="/auth/register" method="POST" class="forms__form">
                        <div class="forms__input-group">
                            <input type="text" name="username" placeholder="Username" class="forms__input" required>
                            <input type="text" name="email" placeholder="Email" class="forms__input" required>
                        </div>
                        <div class="forms__input-group">
                            <input type="text" name="firstname" placeholder="Firstname" class="forms__input" required>
                            <input type="text" name="lastname" placeholder="Lastname" class="forms__input" required>
                        </div>
                        <div class="forms__input-group">
                            <input type="tel" name="phone" placeholder="Phone number" class="forms__input">
                            <input type="date" name="date_of_birth" placeholder="Date of birth" class="forms__input">
                        </div>
                        <div class="forms__input-group">
                            <input type="password" name="password" placeholder="Password" class="forms__input" required>
                            <input type="password" name="password_confirm" placeholder="Confirm Password" class="forms__input" required>
                        </div>
                        <button type="submit" class="forms__button">Register</button>
                    </form>
                </div>
            </div>
    </section>
</main>

<?php partial("footer"); ?>

</body>
</html>
