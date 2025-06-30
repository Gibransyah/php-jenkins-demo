<?php
/**
 * Aplikasi PHP Sederhana untuk Demo Jenkins CI/CD
 */

require_once 'src/Calculator.php';
require_once 'src/UserManager.php';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi PHP Sederhana - Jenkins Demo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .section {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .result {
            background-color: #e8f5e8;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        input, button {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        button {
            background-color: #007cba;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #005a87;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Aplikasi PHP Sederhana</h1>
            <p>Demo Jenkins CI/CD Pipeline dengan Docker</p>
        </div>

        <div class="section">
            <h2>📊 Kalkulator Sederhana</h2>
            <?php
            $calc = new Calculator();
            
            if ($_POST['action'] === 'calculate') {
                $num1 = floatval($_POST['num1']);
                $num2 = floatval($_POST['num2']);
                $operation = $_POST['operation'];
                
                try {
                    switch($operation) {
                        case 'add':
                            $result = $calc->add($num1, $num2);
                            break;
                        case 'subtract':
                            $result = $calc->subtract($num1, $num2);
                            break;
                        case 'multiply':
                            $result = $calc->multiply($num1, $num2);
                            break;
                        case 'divide':
                            $result = $calc->divide($num1, $num2);
                            break;
                    }
                    echo "<div class='result'>Hasil: {$num1} {$operation} {$num2} = {$result}</div>";
                } catch (Exception $e) {
                    echo "<div class='result' style='background-color: #ffe8e8;'>Error: " . $e->getMessage() . "</div>";
                }
            }
            ?>
            
            <form method="POST">
                <input type="hidden" name="action" value="calculate">
                <input type="number" name="num1" placeholder="Angka pertama" step="any" required>
                <select name="operation" required>
                    <option value="add">Tambah (+)</option>
                    <option value="subtract">Kurang (-)</option>
                    <option value="multiply">Kali (×)</option>
                    <option value="divide">Bagi (÷)</option>
                </select>
                <input type="number" name="num2" placeholder="Angka kedua" step="any" required>
                <button type="submit">Hitung</button>
            </form>
        </div>

        <div class="section">
            <h2>ℹ️ Informasi Sistem</h2>
            <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
            <p><strong>Server Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            <p><strong>Environment:</strong> <?php echo $_ENV['APP_ENV'] ?? 'development'; ?></p>
        </div>
    </div>
</body>
</html>
