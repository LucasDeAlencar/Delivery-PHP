<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            background: #111;
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            padding: 16px;
        }
        .box {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 10px;
            padding: clamp(1.25rem, 5vw, 2rem);
            width: 100%;
            max-width: 320px;
        }
        h6 {
            color: #555;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        label { display: block; color: #666; font-size: .78rem; margin-bottom: 4px; }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 11px 12px;
            background: #222;
            border: 1px solid #333;
            border-radius: 6px;
            color: #ccc;
            font-size: 1rem;
            margin-bottom: 1rem;
            -webkit-appearance: none;
        }
        input:focus { outline: none; border-color: #444; }
        button[type="submit"] {
            width: 100%;
            padding: 11px;
            background: #222;
            border: 1px solid #333;
            border-radius: 6px;
            color: #888;
            font-size: 1rem;
            cursor: pointer;
            -webkit-appearance: none;
        }
        button[type="submit"]:hover { background: #2a2a2a; color: #aaa; }
        .erro { color: #c0392b; font-size: .8rem; text-align: center; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="box">
        <h6>Acesso</h6>
        <?php if (session()->has('atencao')): ?>
            <p class="erro"><?= session('atencao') ?></p>
        <?php endif; ?>
        <form method="POST" action="<?= site_url('acesso') ?>">
            <?= csrf_field() ?>
            <label>Nome</label>
            <input type="text" name="nome" value="<?= old('nome') ?>" placeholder="Seu nome" autofocus autocomplete="username">
            <label>Senha</label>
            <input type="password" name="password" placeholder="••••••••" autocomplete="current-password">
            <button type="submit">Entrar</button>
        </form>
    </div>
    <script>
    // Se já tem cookie de acesso salvo, redireciona direto
    (function () {
        const saved = document.cookie.split('; ').find(r => r.startsWith('acesso_admin='));
        if (saved) {
            const val = decodeURIComponent(saved.split('=')[1]);
            try {
                const { nome, password } = JSON.parse(atob(val));
                if (nome && password) {
                    const form = document.querySelector('form');
                    form.querySelector('[name="nome"]').value = nome;
                    form.querySelector('[name="password"]').value = password;
                    form.submit();
                }
            } catch(e) {}
        }
    })();
    </script>
</body>
</html>
