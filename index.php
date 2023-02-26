<?php
require_once('connection.php');

//get categories
$stmt = $conn->prepare("SELECT type_name
    FROM expense_types;");
$stmt->execute();
$expense_types = $stmt->fetchAll();
?>

<head>
    <title>Budget App</title>
    <meta charset="UTF-8" />
    <!-- ✅ Load CSS file for jQuery UI  -->
    <link href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel="stylesheet" />

    <!-- ✅ load jQuery ✅ -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- ✅ load jquery UI ✅ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>
</head>

<form>
    <label for="description">Description:</label>
    <input type="text" id="description" name="description"><br><br>
    <label for="amount">Amount:</label>
    <input type="text" id="amount" name="amount"><br><br>
    <label for="date">Date:</label>
    <input type="date" id="date" name="date" value="<?= date("Y-m-d"); ?>"><br><br>
    <label for="category">Category:</label>
    <select id="category">
        <option value='charity'>charity</option>";
        <?php
        foreach ($expense_types as $type) {
            $type_name = $type['type_name'];
            if ($type_name == 'groceries') {
                echo "<option selected='selected' value='strtolower($type_name)'>$type_name</option>";
            } else {
                echo "<option value='strtolower($type_name)'>$type_name</option>";
            }
        }
        ?>
    </select>
    <br>
    <br>
    <input type="submit" onclick="addTransaction(); return false;" value="Submit">
</form>

<script>
    //$(document).ready(function(){
    function addTransaction() {
        $.ajax({
            type: 'POST',
            data: {
                description: $('#description').val(),
                amount: $('#amount').val(),
                category: $('#category :selected').text(),
                date: $('#date').val()
            },
            url: '/Budget App v4/add_transaction.php',
            success: function(data) {
                $parsed = JSON.parse(data);
                alert($parsed);
                $("#transactions tr").remove();


                // Adding a row inside the tbody.
                $.ajax({
                    type: 'POST',
                    url: '/Budget App v4/get_transactions.php',
                    success: function(data) {

                        parsed_transactions = JSON.parse(data);

                        for (const t in parsed_transactions) {
                            $('#transactions').append(`<tr><td style="width:150px;border:1px solid black;">${parsed_transactions[t].description}</td>
                            <td style="width:150px;border:1px solid black;">${parsed_transactions[t].amount}</td>
                            <td style="width:150px;border:1px solid black;">${parsed_transactions[t].trans_date}</td></tr>`);
                        }
                    }
                });
            }
        });
    }
    //});
</script>

<?php

// initialize the table
$stmt = $conn->prepare("SELECT description, amount, trans_date 
    FROM expenses 
    WHERE YEAR(trans_date) = YEAR(CURRENT_DATE()) AND 
      MONTH(trans_date) = MONTH(CURRENT_DATE());
    ORDER BY trans_date DESC");
$stmt->execute();

// set the resulting array to associative
//$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$result = $stmt->fetchAll();

echo "<table id='transactions' style='border: solid 1px black;'>";
echo "<tr><th>Description</th><th>Amount</th><th>Date</th></tr>";
foreach ($result as $r) {
    echo '<tr><td style="width:150px;border:1px solid black;">' . $r['description'] . '</td><td style="width:150px;border:1px solid black;">' . $r['amount'] . '</td><td style="width:150px;border:1px solid black;">' . $r['trans_date'] . '</td></tr>';
}
echo "</table>";
