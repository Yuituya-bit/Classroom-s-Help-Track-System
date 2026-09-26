<?php
    require_once 'log_in_check.php';

    $file = 'test.txt';
    $call_file = 'call.txt';
    $sintyoku_file = 'sintyoku.txt';
    $nothing_check = !file_exists($file) || filesize($file) === 0;
    $test1 = "";
    $test2 = "";

    if($nothing_check === false){
        if(file_exists($call_file) && filesize($call_file) > 0){
            $call_group = file($call_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }
    }
    if($nothing_check === true){
        header('Location: option3.php');
        exit;
    }
    
    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        //リセット処理
        if (isset($_POST['action2']) && $_POST['action2'] === 'リセット'){
            $nothing_check = !file_exists($file) || filesize($file) === 0;
            $false = false;
            if($nothing_check === false){
                if(file_put_contents($file, '', LOCK_EX) === false){$false = true;}
                if(file_put_contents($call_file, '', LOCK_EX) === false){$false = true;}
                if(file_put_contents($sintyoku_file, '', LOCK_EX) === false){$false = true;}
                $z = "開始";
                $reset = ["1", $z, "2", $z, "3", $z, "4", $z, "5", $z, "6", $z, "7", $z, "8", $z, "9", $z, "10", $z];
                if (file_put_contents($sintyoku_file, implode(PHP_EOL, $reset)) === false) {
                    echo "エラー: ファイル '{$sintyoku_file}' への書き込みに失敗しました。ファイルのパーミッションを確認してください。" . PHP_EOL;
                } else {
                    $nothing_check = true;
                }
            }
            if($false === false){
                header('Location: option3.php');
                exit;
            } else {
                echo "<p>ファイルへの書き込みに失敗しました</p>" . PHP_EOL;
            }
        }
        //一列消去処理
        if (isset($_POST['group'])){
            $delete_string = $_POST['group'];
            if(!empty($delete_string) && file_exists($call_file)){
                $call_line = file($call_file);
                $new_call_line = [];
                foreach ($call_line as $cline){
                    if(strpos($cline, $delete_string) === false){
                        $new_call_line[] = $cline;
                    }
                }
                if(file_put_contents($call_file, implode('', $new_call_line)) === false){
                    echo "<p>ファイルへの書き込みに失敗しました</p>" . PHP_EOL;
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
    <title>入力文字表示プログラム</title>
    <meta http-equiv="refresh" content="5">
    <style></style>
</head>
<body>
<?php if ($nothing_check): ?>
    <form method="POST" action="">
        <h1>権限が未確認のログインは無効です</h1>
    <?php else: ?>
        <?php
            $file = 'test.txt';
            if (file_exists($file)){
                $lines = file($file, FILE_IGNORE_NEW_LINES);
                for ($i=0; $i<8; $i++){
                    if(isset($lines[$i])){
                        if($i === 0){echo "<h1>実験タイトル:" . $lines[$i] . "</h1>";}
                        else{echo "<p> " . $i . ":" . $lines[$i] . "</p>";}
                    }
                }
            }
        ?>
        <form method="POST" action="">
            <input type="hidden" name="action2" value="リセット">
            <button type="submit" class="delete">
                Data Reset
            </button>
        </form>
        <?php if (!empty($call_group)): ?>
            <form method="POST" action="">
                <h2>呼び出し</h2>
                <?php foreach ($call_group as $group): ?>
                    <input  type="radio" 
                            id="group_<?php echo htmlspecialchars($group, ENT_QUOTES, 'UTF-8'); ?>" 
                            name="group" 
                            value="<?php echo htmlspecialchars($group, ENT_QUOTES, 'UTF-8'); ?>">
                    <label for="group_<?php echo htmlspecialchars($group, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($group, ENT_QUOTES, 'UTF-8'); ?>
                    </label>
                    <br>
                <?php endforeach; ?>
                <br>
                <input type="submit" value="対応済み">
            </form>
        <?php endif; ?>
        <br>
        <h2>進捗</h2>
        <?php

        if (file_exists($sintyoku_file)){
            $lines = file($sintyoku_file, FILE_IGNORE_NEW_LINES);
            if($lines !== false && count($lines) > 0){
                for($i = 0; $i < count($lines); $i++){
                    $current = trim($lines[$i]);
                    if(is_numeric($current) && (int)$current >= 1 && (int)$current <= 10){
                        $exnumber = (int)$current;
                        $next_line = $i + 1;
                        if(isset($lines[$next_line])){ 
                            $next_cont = trim($lines[$next_line]);
                            if(!is_numeric($next_cont)  && $next_cont !== ''){                           
                                echo $exnumber . "班:" . $lines[$next_line] . "<br>";
                            }
                        }
                    }
                }
                
            }
        }
        ?>
            
        
    <?php endif; ?>
</body>
</html>