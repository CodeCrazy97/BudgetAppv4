<?php
require_once('connection.php');

try {

    $amount = number_format((float)$_REQUEST['amount'], 2, '.', '');
    if ($amount == 0.00) {
        echo json_encode('Invalid amount');
        exit;
    }

    if ($_REQUEST['category'] == 'charity') {
        $table = 'charity';
        $stmt = $conn->prepare("INSERT INTO charity (description, amount, trans_date)
            VALUES (?, ?, ?)");
        $success = $stmt->execute([$_REQUEST['description'], $_REQUEST['amount'], $_REQUEST['date']]);
    } else {
        $table = 'expenses';
        $stmt = $conn->prepare("INSERT INTO expenses (description, amount, trans_date, expense_type)
            VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([$_REQUEST['description'], $_REQUEST['amount'], $_REQUEST['date'], $_REQUEST['category']]);
    }
    
    if ($table == 'charity') {
        $stmt = $conn->prepare("SELECT SUM(amount) AS 'charity_balance'
            FROM charity;");
        $stmt->execute();
        $charity_balance = $stmt->fetch();
    }

    if ($success) {
        if ($table == 'charity') {
            echo json_encode("Transaction added successfully. New charity balance: " . $charity_balance['charity_balance']);
            exit;
        }
        echo json_encode("Transaction added successfully.");
        exit;
    } else {
        echo json_encode("Transaction was NOT added.");
        exit;
    }
} catch(PDOException $e) {
    echo json_encode('Error: ' . $e->getMessage());
    exit;
}