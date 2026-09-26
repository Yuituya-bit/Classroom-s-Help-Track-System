<?php
    require_once 'log_in_check.php';

    $file = 'test.txt';
    $sintyoku_file = 'sintyoku.txt';
    $test1 = "";
    $test2 = "";
    $false = false;
    $nothing_check = !file_exists($file) || filesize($file) === 0;
    
    if($nothing_check === false){
        header('Location: main3.php');
        exit;
    }
    
    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        //入力前にテキストファイルの中身を確認
        $nothing_check = !file_exists($file) || filesize($file) === 0;
        if($nothing_check === true){
            if (isset($_POST['submit_input'])){
                $test1 = htmlspecialchars($_POST["title"] ?? '', ENT_QUOTES, "UTF-8");
                if(!empty($test1)){
                    if(file_put_contents($file, $test1 . PHP_EOL, FILE_APPEND | LOCK_EX) == false){echo "<p>エラー発生<p>";}
                    else {$nothing_check = false;}
                    for ($i=1; $i <= 6; $i++){
                        $sintyoku_name = "sintyoku" . $i;
                        $sintyoku = htmlspecialchars($_POST[$sintyoku_name] ?? '', ENT_QUOTES, "UTF-8");
                        if(!empty($sintyoku)){
                            if(file_put_contents($file, $sintyoku. PHP_EOL, FILE_APPEND | LOCK_EX) !== false){
                                $nothing_check = false;
                            } else {
                                echo "<p>ファイルへのアクセスに失敗しました</p>";
                                $false = true;
                            }
                        }
                    }
                    if($false === false){
                        header('Location: main3.php');
                        exit;
                    }
                } else {
                    echo "<p class='placeholder-text'>NO 入力</p>";
                }
                
            }elseif(isset($_POST['auto'])){
                $title = $_POST['auto'];
                if($title === ''){
                    $auto = ["実験", "開始", "2割", "4割", "6割", "8割", "終了"];
                } else {
                    $auto = [$title, "開始", "2割", "4割", "6割", "8割", "終了"];
                }
                if (file_put_contents($file, implode(PHP_EOL, $auto)) === false) {
                    echo "エラー: ファイル '{$file}' への書き込みに失敗しました。ファイルのパーミッションを確認してください。" . PHP_EOL;
                } else {
                    header('Location: main3.php');
                    exit;
                }
            }
        } else {
            //main.phpに遷移
            header('Location: main3.php');
            exit;
        }
    }


?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>実験初期設定フォーム</title>
    <style></style>
</head>
<body>
    <form method="POST" action="">
        <h1>初期設定</h1>
        <label for="user_input">実験タイトル<br></label>
        <input type="text" id="user_input" name="title" placeholder="タイトル">
        <label for="user_input"><br>進捗設定<br></label>
        <input type="text" id="user_input" name="sintyoku1" placeholder="進捗1"><br>
        <input type="text" id="user_input" name="sintyoku2" placeholder="進捗2"><br>
        <input type="text" id="user_input" name="sintyoku3" placeholder="進捗3"><br>
        <input type="text" id="user_input" name="sintyoku4" placeholder="進捗4"><br>
        <input type="text" id="user_input" name="sintyoku5" placeholder="進捗5"><br>
        <input type="text" id="user_input" name="sintyoku6" placeholder="進捗6"><br>
        <input type="submit" name="submit_input" value="GO">
    </form>
    <form method="POST" action="">
        <input type="text" name="auto" placeholder="タイトル">
        <button type="submit" class="delete">
            割合表示(自動)
        </button>
    </form>
</body>
</html>