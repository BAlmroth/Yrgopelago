<?php
session_start();
require __DIR__ . '/../app/autoload.php';
require __DIR__ . '/header.php';
?>

<section class="login">
    <h1>Login</h1>

    <!-- show errors -->
    <?php if (!empty($_SESSION['login_error'])): ?>
        <div class="loginError">
            <?= htmlspecialchars($_SESSION['login_error'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>

    <form action="<?= $config['base_url'] ?>/app/loginValidate.php" method="post">
        <div class="loginForm">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Benita" required>
        </div>
        <div class="loginForm">
            <label for="apikey">API Key</label>
            <input type="password" name="password" id="apikey" placeholder="Enter your API key" required>
        </div>
        <button type="submit">Login</button>
    </form>
    </section>

<?php require __DIR__ . '/footer.php'; ?>
