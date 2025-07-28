<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .usuario-row:hover {
            background-color: #f8f9fa !important;
            transition: background-color 0.2s ease;
        }
        .usuario-row {
            transition: background-color 0.2s ease;
        }
        .table-responsive .table tbody tr:hover {
            transform: scale(1.01);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?= $titulo ?></h4>
                        <p class="card-text">Dados dos usuários (Acesso Público)</p>
                        
                        <div class="alert alert-info" role="alert">
                            <strong>Acesso Público:</strong> Você está visualizando esta página sem autenticação.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nome Completo</th>
                                        <th>E-mail</th>
                                        <th>CPF</th>
                                        <th>Ativo</th>
                                        <th>Situação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($usuarios)): ?>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <tr class="usuario-row">
                                                <td><?= esc($usuario->id) ?></td>
                                                <td><?= esc($usuario->nome) ?></td>
                                                <td><?= esc($usuario->email) ?></td>
                                                <td><?= esc($usuario->cpf) ?></td>
                                                <td>
                                                    <?php if ($usuario->ativo && $usuario->deletado_em == null): ?>
                                                        <span class="badge bg-success">Ativo</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Inativo</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($usuario->deletado_em == null): ?>
                                                        <span class="badge bg-success">Disponível</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Excluído</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Nenhum usuário encontrado</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <a href="<?= site_url('login') ?>" class="btn btn-primary">Fazer Login</a>
                            <a href="<?= site_url('/') ?>" class="btn btn-secondary">Voltar ao Início</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>