<?php
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if($_POST["select"] === "学生"){
            header('Location: student3.php');
            exit;
        } elseif($_POST["select"] === "教師"){
            header('Location: password.php');
            exit;
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
        <h1>ユーザータイプ選択</h1>
        <p>ユーザータイプを選んでください</p>
        <input type="radio" id="student" name="select" value="学生">
        <label for="student">学生</label>
        <input type="radio" id="teacher" name="select" value="教師">
        <label for="student">教師</label><br><br>
        <input type="submit" value="開始">
    </form>
</body>
</html>