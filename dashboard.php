<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        /* Your CSS styles here */

        /* Container for the main content */
        #page {
            margin-top: 70px;
            margin-left: 15%;
            margin-right: 70px;
            display: grid;
            width: 70%;
           
            grid-template:
                [header-left] "head head" 30px [header-right]
                [main-left] "nav  main" 1fr [main-right]
                [footer-left] "foot  foot" 30px [footer-right]
                / 400px 1fr;
        }
 

        header {
            background-color: 	#e1edf0; 
            grid-area: head;
            text-align: center;
        }

        nav {
            background-color: #c5e3e7;
            grid-area: nav;
        }

        main {
            background-color: 	#afcace;
            grid-area: main;
        }

        footer {
            background-color:  	 	#afcace;
            grid-area: foot;
            text-align: center;
        
        }

        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .house-postings {
            width: 100%;
            margin-top: 20px;
        }

        .house-posting {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            
        }

        .logout-container {
            background: #073763;
            color: blue;
            border: none;
            border-radius: 10px;
            font-weight: 50;
            position: fixed;
            bottom: 10px;
            right: 10px;
            padding: 10px 20px;
        }

        .logcolor{
            color:black;
        }
        #add_house_form{

    margin-top: 25% ;}
    

        h5{
        text-decoration: underline;}
        h5 div{
            margin-bottom: 10%;
        }

button {
    cursor: pointer;
}

    </style>


<div id="page">
    <?php
    session_start();

    // Check if the user is not authenticated
    if (!isset($_SESSION['user_id'])) {
        // Redirect the user to the login page
        header("Location: login_process.php");
        exit(); // Stop further execution of the script
    }

    // Continue with your existing code for the dashboard page
    include 'db_connect.php'; // Include your database connection script

    // Retrieve the user's ID from the session
    $user_id = $_SESSION['user_id'];

    // Query the database to retrieve user-specific data
    $sql = "SELECT * FROM users WHERE id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Fetch user-specific data
        $user_data = $result->fetch_assoc();
        // Display the user's personalized web page
        echo "<header>Σύστημα διαχείρισης αγγελιών (καλώς ήλθες {$user_data['username']})</header>";

        // Display existing house postings
        echo "<main>";

        echo "<h5><div>Λίστα αγγελιών</div></h5>";
        $sql = "SELECT * FROM house_postings WHERE user_id = $user_id";
        $result1 = $conn->query($sql);
        if ($result1->num_rows == 0) {
            // No house postings found for the user
            echo "<p>No house postings found.</p>";
        } else {
            // Display existing house postings for the user
            echo "<div class='house-postings'>";
            while ($row = $result1->fetch_assoc()) {

                echo "<div class='house-posting'";
                echo "<strong></strong> " .$row['area']. " ,"  ;
                echo "<strong></strong> " . $row['price'] . " ευρώ" ;
                echo "<strong></strong> " . $row['availability']. ", "  ;
                echo "<strong></strong> " . $row['size']. " τ.μ."  ;
                echo "<a href='#' class='delete_posting' data-id='" . $row['id'] . "'><h5>Διαγραφή<h5></a>";
                echo "</div>";
            }
            echo "</div>";
        }
        echo "</main>";

        // Form to add new house posting
        echo "<nav>";
        echo "<form id='add_house_form'>";
        echo "<label>Τιμή:</label> <input type='number' name='price' required><br>";
        echo "<label>Περιοχή:</label>";
        echo "<select name='area'>";
        echo "<option value='Αθήνα'>Αθήνα</option>";
        echo "<option value='Θεσσαλονίκη'>Θεσσαλονίκη</option>";
        echo "<option value='Πάτρα'>Πάτρα</option>";
        echo "<option value='Ηράκλειο'>Ηράκλειο</option>";
        echo "</select><br>";
        echo "<label>Διαθεσιμότητα:</label>";
        echo "<select name='availability'>";
        echo "<option value='Ενοικίαση'>Ενοικίαση</option>";
        echo "<option value='Πώληση'>Πώληση</option>";
        echo "</select><br>";
        echo "<label>Εμβαδόν:</label> <input type='number' name='size' required><br>";
        echo "<button type='submit' name='add_house'>Add house Posting</button>";
        echo "</form>";
        echo "</nav>";
    } else {
        echo "Error: User not found.";
    }
  
    $conn->close();
    ?>
    <footer>All rights reserved</footer>
</div>


<button class="logout-container"  onclick="window.location.href = 'logout.php';"><h6 class="logcolor">Logout</h6></button>


   <script>
 $(document).ready(function() {
    // Function to refresh house postings and manage the "No house postings found" message
    function refreshhousePostings() {
        $('.house-postings').load(location.href + ' .house-postings', function() {
            // Show or hide the "No house postings found" message based on the presence of postings
            if ($('.house-postings').children().length === 0) {
                $('.no-house-postings').show();
            } else {
                $('.no-house-postings').hide();
            }
        });
    }

    // Delete house posting via AJAX
    $(document).on('click', '.delete_posting', function(e) {
        e.preventDefault();
        var postingId = $(this).data('id');
        $.ajax({
            url: 'delete_house_posting.php',
            method: 'POST',
            data: { delete_id: postingId },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.status === 'success') {
                    refreshhousePostings();
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });

    // Handle form submission via AJAX
    $('#add_house_form').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'add_house_posting.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.status === 'success') {
                    $('#add_house_form')[0].reset();
                    if ($('.house-postings').length) {
                        refreshhousePostings();
                    } else {
                        var newRowHTML = '<div class="house-posting">';
                            newRowHTML += '<strong>' + response.area + ',</strong>'; // Add comma after the area
                            newRowHTML += '<strong>$' + response.price + ' ευρώ</strong>'; // Add dollar sign and "ευρώ" after the price
                            newRowHTML += '<strong>' + response.availability + ',</strong>'; // Add comma after the availability
                            newRowHTML += '<strong>' + response.size + ' τ.μ.</strong>'; // Add "τ.μ." after the size
                            newRowHTML += '<a href="#" class="delete_posting" data-id="' + response.id + '"><h5>Διαγραφή</h5></a>';
                            newRowHTML += '</div>';
                            $('.house-postings').append(newRowHTML);

                        refreshhousePostings();
                        $('.no-house-postings').hide(); // Hide "No house postings found" message
                    }
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
    
    // Initially hide the "No house postings found" message if there are house postings
    if ($('.house-postings').children().length > 0) {
        $('.no-house-postings').hide();
    }
});

</script>


</body>
</html>
