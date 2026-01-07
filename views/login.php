<?php
session_start();
require __DIR__ . '/../app/autoload.php';
require __DIR__ . '/header.php';
?>

<section>
    <h1>Login</h1>

    <?php if (!empty($_SESSION['login_error'])): ?>
        <div style="color:red;">
            <?= htmlspecialchars($_SESSION['login_error'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>

    <form action="<?= $config['base_url'] ?>/app/loginValidate.php" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input class="form-control" type="text" name="username" id="username" placeholder="Benita" required>
            <small class="form-text">Username</small>
        </div>

        <div class="mb-3">
            <label for="apikey" class="form-label">API Key</label>
            <input class="form-control" type="password" name="password" id="apikey" placeholder="Enter your API key" required>
            <small class="form-text">API key</small>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    </section>

<?php require __DIR__ . '/footer.php'; ?>
