<?php
    session_start();
    $pass = 0;
    $each_pass = "execute_main_create";
    $hashed_pass = password_hash($each_pass, PASSWORD_DEFAULT);
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if(password_verify(htmlspecialchars($_POST["pass"]), $hashed_pass)){
            $_SESSION['log_in'] = true;
            header('Location: option3.php');
            exit;
        } else {
            $pass = 1;
        }
    } 
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント選択フォーム</title>
    <style></style>
</head>
<body>
    <form method="POST" action="">
        <h1>パスワードを入力してください</h1>
        <input type="password" id="student" name="pass" placeholder="パスワード">
        <input type="submit" value="Done">
    </form>
    <?php if($pass === 1): ?>
        <p>パスワードが異なります</p>
    <?php endif; ?>
</body>
</html>