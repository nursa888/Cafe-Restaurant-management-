<?php require_once 'includes/auth.php';header('Location: '.BASE.(empty($_SESSION['user'])?'/login.php':'/admin/dashboard.php'));
