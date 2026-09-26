<?php
    $file = 'test.txt';
    $call_file = 'call.txt';
    $sintyoku_file = 'sintyoku.txt';
    $nothing_check = !file_exists($file) || filesize($file) === 0;
    $options = []; // 選択肢を格納する配列

    if ($nothing_check === false){
        if (file_exists($file)) {
            // ファイルを行ごとに配列として読み込む
            // FILE_IGNORE_NEW_LINES は各行の末尾の改行文字を削除するオプション
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // 空行もスキップ

            // 読み込んだ行をそのまま選択肢として使用
            for ($i=0; $i<5; $i++){
                if(isset($lines[$i + 1])){
                    $options[$i] = $lines[$i + 1];
                }
            }
        }
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $current_lines = [];
            $newlines = [];
            if(file_exists($sintyoku_file)){
                $current_lines = file($sintyoku_file, FILE_IGNORE_NEW_LINES);
                if($current_lines === false){
                    $current_lines = [];
                }
            } else {
                $current_lines = [];
            }

            $nothing_check = !file_exists($file) || filesize($file) === 0;
            if($nothing_check === false){
                //number名義のポストを処理
                if(isset($_POST["calling"])){
                    header('Location: signal.php');
                    exit;
                }
                if(isset($_POST["number"])){
                    $number = (int)htmlspecialchars($_POST["number"] ?? '', ENT_QUOTES, "UTF-8");
                }
                //sintyoku名義のポストを処理
                if(isset($_POST["sintyoku"])){
                    $sintyoku = htmlspecialchars($_POST["sintyoku"] ?? '', ENT_QUOTES, "UTF-8");
                }
                $newlines = $current_lines;
                $foundup = false;
                foreach($current_lines as $i => $line_cont){
                    $trimmed = trim($line_cont);
                    if(is_numeric($trimmed) && (int)$trimmed === $number){
                        $next_num = $i + 1;
                        if(isset($current_lines[$next_num]) && !is_numeric(trim($current_lines[$next_num]))){
                            if($sintyoku !== ''){
                                $newlines[$next_num] = $sintyoku;
                            } 
                            $foundup = true;
                            break;
                        } 
                    }
                }
                if (file_put_contents($sintyoku_file, implode(PHP_EOL, $newlines), LOCK_EX) === false) {
                    echo "エラー: ファイル '{$sintyoku_file}' への書き込みに失敗しました。ファイルのパーミッションを確認してください。" . PHP_EOL;
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
    <h1>先生呼び出しシステム</h1>
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
        <?php //進捗のプルダウンを作成 ?>
        <?php if (!empty($options)): ?>
            <select name="sintyoku" id="sintyoku">
                <?php foreach ($options as $option_text): ?>
                    <option value="<?php echo htmlspecialchars($option_text, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($option_text, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <input type="submit" value="進捗送信"><br>
    </form>
    <form method="POST" action="">
            <input type="hidden" name="calling" value="呼び出し"><br>
            <button type="submit" class="delete">
                呼び出しページへ
            </button>
        </form>
<?php endif; ?>
</body>
</html>