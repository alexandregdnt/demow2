<header class="header">
    <nav class="nav">
        <div class="nav__container">
            <div class="nav__row">
                <div class="nav__col">
                    <a href="/" class="nav__logo">
                        <img src="/assets/img/logo.png" alt="Logo" class="nav__logo-img">
                    </a>
                </div>
                <div class="nav__col">
                    <ul class="nav__list">
                        <li class="nav__list-item">
                            <a href="/" class="nav__list-link">Home</a>
                        </li>
                        <?php if (isset($_SESSION['user'])): ?>
                            <li class="nav__list-item">
                                <a href="/post/create" class="nav__list-link">Create Post</a>
                            </li>
                            <li class="nav__list-item">
                                <a href="/user/<?php echo unserialize($_SESSION['user'])->getId(); ?>" class="nav__list-link">User Profile</a>
                            </li>
                            <li class="nav__list-item">
                                <a href="/auth/logout" class="nav__list-link">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav__list-item">
                                <a href="/auth/login" class="nav__list-link">Login</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
