<?php
session_start();

// ログインしていない、またはセッションが無効な場合はログインページへリダイレクト
// $_SESSION['logged_in'] が設定されていて、かつそれが true であることを確認
if (!isset($_SESSION['log_in']) || $_SESSION['log_in'] !== true) {
    header('Location: index.php');
    exit;
}
?>