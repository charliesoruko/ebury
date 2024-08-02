

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Statement Link</title>
</head>
<body>



<?php


include "eburyapi.php";

$accesstoken=$_GET["accesstoken"];
$filetype=$_GET["filetype"];
$account_id=$_GET["account_id"];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the date inputs from the form submission
    $fromDateInput = $_POST['fromDateInput'];
    $toDateInput = $_POST['toDateInput'];

    // Display the submitted dates
    if (!empty($fromDateInput) && !empty($toDateInput)) {
        // Ensure the dates are in the correct format
        $formattedFromDate = date('Y-m-d\TH:i:s', strtotime($fromDateInput));
        $formattedToDate = date('Y-m-d\TH:i:s', strtotime($toDateInput));
        echo "<p>Submitted Date Range: From " . htmlspecialchars($formattedFromDate) . " to " . htmlspecialchars($formattedToDate) . "</p>";


        create_statement($accesstoken,$filetype,$account_id,$formattedFromDate,$formattedToDate);




    } else {
        echo "<p>Please enter both from and to dates.</p>";
    }
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?account_id='.$account_id."&accesstoken=".$accesstoken."&filetype=".$filetype ?>" >
    <label for="fromDateInput">From Date:</label>
    <input type="datetime-local" id="fromDateInput" name="fromDateInput" required>
    
    <label for="toDateInput">To Date:</label>
    <input type="datetime-local" id="toDateInput" name="toDateInput" required>

    
    <button type="submit">Submit</button>
</form>

</body>
</html>

<?php




//create_statement($accesstoken,$filetype,$account_id);

?>