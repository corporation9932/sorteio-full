<?php
session_start();
require_once '../config/database.php';

// Verificar login do admin
if (!isset($_SESSION['admin_logged'])) {
    if ($_POST['username'] ?? '' && $_POST['password'] ?? '') {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT * FROM admins WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$_POST['username']]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($_POST['password'], $admin['password'])) {
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_id'] = $admin['id'];
        } else {
            $error = "Credenciais inválidas";
        }
    }
    
    if (!isset($_SESSION['admin_logged'])) {
        ?>
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin - Login</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card mt-5">
                            <div class="card-header">
                                <h4>Login Administrativo</h4>
                            </div>
                            <div class="card-body">
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?= $error ?></div>
                                <?php endif; ?>
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Usuário</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Senha</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Entrar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

require_once '../classes/Campaign.php';
$campaign = new Campaign();
$campaigns = $campaign->getActiveCampaigns();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Painel Admin</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="?page=campaigns" class="list-group-item list-group-item-action">
                        <i class="bi bi-megaphone"></i> Campanhas
                    </a>
                    <a href="?page=orders" class="list-group-item list-group-item-action">
                        <i class="bi bi-cart"></i> Pedidos
                    </a>
                    <a href="?page=users" class="list-group-item list-group-item-action">
                        <i class="bi bi-people"></i> Usuários
                    </a>
                    <a href="?page=reports" class="list-group-item list-group-item-action">
                        <i class="bi bi-graph-up"></i> Relatórios
                    </a>
                    <a href="?page=contacts" class="list-group-item list-group-item-action">
                        <i class="bi bi-envelope"></i> Contatos
                    </a>
                </div>
            </div>
            <div class="col-md-9">
                <?php
                $page = $_GET['page'] ?? 'dashboard';
                switch ($page) {
                    case 'campaigns':
                        include 'pages/campaigns.php';
                        break;
                    case 'orders':
                        include 'pages/orders.php';
                        break;
                    case 'users':
                        include 'pages/users.php';
                        break;
                    case 'reports':
                        include 'pages/reports.php';
                        break;
                    case 'contacts':
                        include 'pages/contacts.php';
                        break;
                    default:
                        include 'pages/dashboard.php';
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>