<div class="login-container">
    <div class="login-card">
        <h2>Administrácia</h2>
        <p class="login-subtitle">Prihláste sa do svojho účtu</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" class="login-form">
            <?= \App\Helpers\View::csrfField() ?>
            <div class="form-group">
                <label for="username">Používateľské meno</label>
                <div class="input-wrapper">
                    <span class="input-icon">👤</span>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        value="<?php echo htmlspecialchars($old['username'] ?? ''); ?>" 
                        placeholder="Zadajte meno" 
                        required 
                        autofocus
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="password">Heslo</label>
                <div class="input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Zadajte heslo" 
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <span>Prihlásiť sa</span>
                <span class="btn-icon">→</span>
            </button>
        </form>
    </div>
</div>
