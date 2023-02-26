<?php
require_once('connection.php');

$stmt = $conn->prepare("SELECT description, amount, expense_type, trans_date 
    FROM expenses 
    WHERE YEAR(trans_date) = YEAR(CURRENT_DATE()) AND MONTH(trans_date) = MONTH(CURRENT_DATE())
    ORDER BY trans_date DESC");
$stmt->execute();
$results = $stmt->fetchAll();

echo json_encode($results);
exit;