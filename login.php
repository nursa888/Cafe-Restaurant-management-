<?php require_once 'includes/auth.php';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){csrf_check();
 $s=$pdo->prepare('SELECT u.*,r.slug FROM users u JOIN roles r ON r.id=u.role_id WHERE username=?');$s->execute([trim($_POST['username']??'')]);$u=$s->fetch();
 if($u&&password_verify($_POST['password']??'',$u['password_hash'])&&!$u['is_active']){$err='Your account is awaiting administrator approval.';}
 elseif($u&&password_verify($_POST['password']??'',$u['password_hash'])){session_regenerate_id(true);$_SESSION['user']=['id'=>$u['id'],'name'=>$u['full_name'],'role'=>$u['slug']];audit('login');
  $go=['kitchen'=>'/kitchen/dashboard.php','waiter'=>'/waiter/dashboard.php','cashier'=>'/pos/index.php'][$u['slug']]??'/admin/dashboard.php';header('Location: '.BASE.$go);exit;}
 if(!$err)$err='Invalid username or password.';}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="<?=BASE?>/assets/style.css" rel="stylesheet"></head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh"><div><ul class="nav nav-tabs" style="width:340px"><li class="nav-item"><a class="nav-link active" href="login.php">Login</a></li><li class="nav-item"><a class="nav-link" href="register.php">Register</a></li></ul>
<form method="post" class="card p-4 shadow rounded-top-0" style="width:340px"><?=csrf_field()?>
<h4 class="text-center">☕ <?=h(setting('restaurant_name'))?></h4><?php if(isset($_GET['registered'])):?><div class="alert alert-success py-2">Registered. Wait for an administrator to approve your account.</div><?php endif;?><?php if($err):?><div class="alert alert-danger py-2"><?=h($err)?></div><?php endif;?>
<input name="username" class="form-control mb-2" placeholder="Username" required autofocus><input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
<button class="btn text-white" style="background:var(--brand)">Sign in</button></form></div></body></html>
