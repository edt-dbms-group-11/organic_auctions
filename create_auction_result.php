<?php include_once("header.php")?>
<?php include_once("create_auction.php")?>
<?php include_once("database.php"); ?>

<div class="container my-5">

<?php
    global $connection;
    if (!isset($_SESSION) || $_SESSION == null) {
    echo('<div class="text-center">You\'re not logged in. Please re-login if this was a mistake</div>');
    header('refresh:3;url=browse.php');
    }

    // Check if the form was submitted
    if (isset($_POST["auctionsubmit"])) {
   
      // Extract form data into variables. 
      $title = $_POST['auctionTitle'];
      $details = $_POST['auctionDetails'];
      $startPrice = $_POST['auctionStartPrice'];
      $reservePrice = $_POST['auctionReservePrice'];
      $endDate = $_POST['auctionEndDate'];
      $formattedEndDate = date('Y-m-d H:i:s', strtotime($endDate));

      $seller_id = $_SESSION['id'];
      $errors = [];

    // Check if title is not empty
      if (empty($title)) {
          $errors[] = "Title cannot be empty.";
      }

    // Validate if each item ID belongs to the user
        error_log(json_encode($_POST['selectedItems']));
        foreach ($_POST['selectedItems'] as $itemId) {
          $validateItemQuery = "SELECT user_id FROM Item WHERE id = $itemId";
          $validateItemResult = mysqli_query($connection, $validateItemQuery);

          if (!$validateItemResult) {
              echo "Database error: " . mysqli_error($connection);
              exit;
          }

          $row = mysqli_fetch_assoc($validateItemResult);

          if ($row['user_id'] != $_SESSION['id']) {
              $errors[] = "Invalid item selection.";
          }
        }

      // Check if start price is a positive number
      $startPriceInt = filter_var($startPrice, FILTER_VALIDATE_INT);
      if ((!$startPriceInt || $startPriceInt < 1 || $startPrice != $startPriceInt)) {
        $errors[] = "Start price must be a positive number.";
      } 

      // Check if reserve price is a positive number (if provided)
      $reservePriceInt = filter_var($reservePrice, FILTER_VALIDATE_INT);
      if (!isset($reservePrice) || trim($reservePrice) === '') {
          // If reserve price is blank, assign the start price to it
          $reservePrice = $startPrice;
      } elseif (!$reservePriceInt || $reservePriceInt < 1 || $reservePrice != $reservePriceInt) {
          // If reserve price is provided but not a positive number, show an error
          $errors[] = "Reserve price must be a positive number.";
      } elseif ($reservePrice < $startPrice) {
          // If reserve price is lower than start price, show an error
          $errors[] = "Reserve price cannot be lower than the start price.";
      }

      // Validate end date - you may want to check if the date is in the future
      if (empty($endDate) || strtotime($endDate) <= time()) {
          $errors[] = "Invalid end date.";
      }

      if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        echo '<script>window.location.href = "create_auction.php";</script>';
        exit();
    } else {
        $query = "INSERT INTO Auction (title, description, seller_id, start_price, reserved_price, current_price, end_time) 
                VALUES ('$title', '$details', $seller_id,  $startPrice, $reservePrice, $startPrice, '$formattedEndDate')";

        // Execute the query
        $result = mysqli_query($connection, $query);

        if ($result) {
            // If the insertion was successful, get the auction ID
            $auction_id = mysqli_insert_id($connection);

            // Now, add rows to the Auction_Product table
            foreach ($_POST['selectedItems'] as $item_id) {
                $insertProductQuery = "INSERT INTO Auction_Product (item_id, auction_id) VALUES ('$item_id', '$auction_id')";
                $insertProductResult = mysqli_query($connection, $insertProductQuery);

                if (!$insertProductResult) {
                    // Handle error if any
                    echo('<div class="text-center text-danger">Error adding items to the Auction Product table. Please try again.</div>');
                    echo "error: " . mysqli_error($connection);
                    exit();
                }
            }
            // Notify the user that the auction and associated items were added successfully
            echo('<div class="text-center">Auction and associated items successfully created! <a href="FIXME">View your new listing.</a></div>');

            //Update item availability in the Item table after it has successfully been added to the Auction and Auction_Product table
            foreach ($_POST['selectedItems'] as $itemId) {
                // Validate if the item belongs to the current user
                $validateItemQuery = "SELECT user_id FROM Item WHERE id = $itemId";
                $validateItemResult = mysqli_query($connection, $validateItemQuery);
            
                if (!$validateItemResult) {
                    // Handle the database error (e.g., log, display an error message)
                    echo "Database error: " . mysqli_error($connection);
                    exit; 
                }
            
                $itemData = mysqli_fetch_assoc($validateItemResult);
            
                if ($itemData['user_id'] !== $_SESSION['id']) {
                    // Handle the case where the item doesn't belong to the current user
                    echo "Error: The item with ID $itemId does not belong to the current user.";
                    exit;
                }
            
                // Update the item status
                $updateQuery = "UPDATE Item SET is_available = 0 WHERE id = $itemId";
                $updateResult = mysqli_query($connection, $updateQuery);
            
                if (!$updateResult) {
                    // Handle the database error (e.g., log, display an error message)
                    echo "Database error: " . mysqli_error($connection);
                    exit; 
                }
            }
            
        } else {
            // If there was an error, provide an error message
            echo('<div class="text-center text-danger">Error creating auction. Please try again.</div>');
            echo "error:" .mysqli_error($connection);
        }
    }
}
?>

</div>

<?php include_once("footer.php")?>