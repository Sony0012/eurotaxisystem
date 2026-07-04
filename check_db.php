<?php
$dsn = "mysql:host=127.0.0.1;dbname=eurotaxi;charset=utf8mb4";
$user = "root";
$pass = "";
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Expenses:\n";
    $stmt = $pdo->query("SELECT id, amount, date, description FROM expenses WHERE amount < 0");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

    echo "\nSalaries:\n";
    $stmt = $pdo->query("SELECT id, total_salary, pay_date FROM salaries WHERE total_salary < 0");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

    echo "\nMaintenance:\n";
    $stmt = $pdo->query("SELECT id, cost, date_started FROM maintenance WHERE cost < 0");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (\PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
