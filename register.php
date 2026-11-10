<?php require_once 'includes/auth.php';$err='';$v=['full_name'=>'','username'=>'','phone'=>'','role'=>''];
$roles=$pdo->query("SELECT id,name FROM roles WHERE slug<>'admin' ORDER BY id")->fetchAll(PDO::FETCH_KEY_PAIR);
if($_SERVER['REQUEST_METHOD']==='POST'){csrf_check();
 foreach($v as $k=>$_)$v[$k]=trim($_POST[$k]??'');$pw=$_POST['password']??'';$rid=(int)$v['role'];
 if(mb_strlen($v['full_name'])<3||mb_strlen($v['full_name'])>100)$err='Enter your full name (3-100 characters).';
 elseif(!preg_match('/^[A-Za-z0-9_.]{3,30}$/',$v['username']))$err='Username: 3-30 letters, numbers, dot or underscore.';
 elseif(!isset($roles[$rid]))$err='Choose a valid role.';
 elseif($v['phone']!==''&&!preg_match('/^[0-9+ ]{7,20}$/',$v['phone']))$err='Enter a valid phone number.';
 elseif(strlen($pw)<8||!preg_match('/[A-Za-z]/',$pw)||!preg_match('/\d/',$pw))$err='Password must be at least 8 characters with letters and numbers.';
 elseif($pw!==($_POST['confirm']??''))$err='Passwords do not match.';
 else{$c=$pdo->prepare('SELECT COUNT(*) FROM users WHERE username=?');$c->execute([$v['username']]);
  if($c->fetchColumn())$err='That username is already taken.';
  else{try{$pdo->beginTransaction();
   $pdo->prepare('INSERT INTO users(role_id,username,password_hash,full_name,is_active) VALUES(?,?,?,?,0)')->execute([$rid,$v['username'],password_hash($pw,PASSWORD_DEFAULT),$v['full_name']]);
   $uid=(int)$pdo->lastInsertId();
   $pdo->prepare('INSERT INTO employees(user_id,full_name,department,phone,hired_on) VALUES(?,?,?,?,CURDATE())')->execute([$uid,$v['full_name'],$roles[$rid],$v['phone']]);
   $pdo->prepare('INSERT INTO notifications(message) VALUES(?)')->execute(["New registration awaiting approval: {$v['username']} ({$roles[$rid]})"]);
   $pdo->commit();header('Location: login.php?registered=1');exit;}
  catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$err='Registration failed. Please try again.';}}}}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="<?=BASE?>/assets/style.css" rel="stylesheet"></head>
<body class="d-flex align-items-center justify-content-center py-4" style="min-height:100vh"><div>
<ul class="nav nav-tabs" style="width:360px"><li class="nav-item"><a class="nav-link" href="login.php">Login</a></li><li class="nav-item"><a class="nav-link active" href="register.php">Register</a></li></ul>
<form method="post" class="card p-4 shadow rounded-top-0" style="width:360px"><?=csrf_field()?>
<h5 class="text-center mb-1">Create an account</h5><p class="text-center text-muted small">An administrator must approve your account before you can log in.</p>
<?php if($err):?><div class="alert alert-danger py-2"><?=h($err)?></div><?php endif;?>
<input name="full_name" class="form-control mb-2" placeholder="Full name" value="<?=h($v['full_name'])?>" required>
<input name="username" class="form-control mb-2" placeholder="Username" value="<?=h($v['username'])?>" required>
<select name="role" class="form-select mb-2" required><option value="">Select role</option><?php foreach($roles as $id=>$n):?><option value="<?=$id?>" <?=$v['role']==$id?'selected':''?>><?=h($n)?></option><?php endforeach;?></select>
<input name="phone" class="form-control mb-2" placeholder="Phone (optional)" value="<?=h($v['phone'])?>">
<input type="password" name="password" class="form-control mb-2" placeholder="Password (min 8, letters + numbers)" required>
<input type="password" name="confirm" class="form-control mb-3" placeholder="Confirm password" required>
<button class="btn text-white" style="background:var(--brand)">Register</button></form></div></body></html>
