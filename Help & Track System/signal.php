<?php
    $file = 'test.txt';
    $call_file = 'call.txt';
    $nothing_check = !file_exists($file) || filesize($file) === 0;
    $options = []; // 選択肢を格納する配列

    if ($nothing_check === false){
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $nothing_check = !file_exists($file) || filesize($file) === 0;
            if($nothing_check === false){
                //number名義のポストを処理
                if(isset($_POST["number"])){
                    $number = htmlspecialchars($_POST["number"] ?? '', ENT_QUOTES, "UTF-8");
                }
                //sintyoku名義のポストを処理
                if(isset($_POST["sintyoku"])){
                    $sintyoku = htmlspecialchars($_POST["sintyoku"] ?? '', ENT_QUOTES, "UTF-8");
                }
                //呼び出し用の文字列を作成＆ファイルに書き込み
                if(!empty($number) && !empty($sintyoku)){
                    $ent_call = $number . "班:" . $sintyoku;
                    if(file_put_contents($call_file, $ent_call . PHP_EOL, FILE_APPEND | LOCK_EX) === false){
                        echo "<p>呼び出しに失敗しました。再送信してください</p>" . PHP_EOL;
                    } else {
                        header('Location: student3.php');
                    }
                }
            }
        }
    }

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>生徒側呼び出しシステム</title>
    <style></style>
</head>
<body>
<?php if ($nothing_check): ?>
    <h1>実験が開始されていません<br>しばらくお待ちください</h1>
<?php else: ?>
    <h1>先生呼び出し</h1>
    <form method="POST" action="">
        <?php //班番号のプルダウンを作成 ?>
        <?php $number = [1,2,3,4,5,6,7,8,9,10]; ?>
        <?php if (!empty($number)): ?>
            <select name="number" id="number">
                <?php foreach ($number as $num): ?>
                    <option value="<?php echo htmlspecialchars($num, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($num . '班', ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <input type="text" name="sintyoku" placeholder="要件">
        <input type="submit" value="呼び出し"><br>
    </form>
    <?php
        if(!empty($ent_call)){
            echo "<p>前回の送信:" . $ent_call . "</p>"; 
        }
    ?>
<?php endif; ?>
</body>
</html>