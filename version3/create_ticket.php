<?php

session_start();
require_once('functions.php');

// Make sure only people logged in can view this page
mustBeLoggedIn();

// Attempt to add the ticket to the DB
if (isset($_POST['submit_ticket'])) {

    require('classes/Ticket.php');

    $ticket = new Ticket();

    $con = $ticket->connect();

    $username = $_SESSION['Username'];
    $priority = $_POST['priority'];
    $title = $_POST['title'];
    $desc = $_POST['description'];

    // Attempt to add the ticket to the DB
    if ($status = $ticket->newTicket($con, $username, $priority, $title, $desc)) {

        // Ticket was added to the DB
        $msg = "Ticket was successfully submitted!";
        echo '<script type="text/javascript">alert("'.$msg.'");</script>';
        $con->close();
        header("refresh:0; url=my_tickets.php");
    } else {

        // Ticket couldn't be added to the DB
        $errormsg = $ticket->getError();
        echo '<script type="text/javascript">alert("'.$errormsg.'");</script>';
        $con->close();
        header("refresh:0; url=index.php");
    }

}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
  <title>Create A New Ticket</title>    
  
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome (for the icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <!-- Our CSS file for the site after the login page -->
    <link rel="stylesheet" href="styles/stylesheet.css">
  
</head>
  <body>

    <div class="wrapper">
        <!-- The sidebar and navigation links -->
        <nav id="desktopNav">
            <ul class="list-unstyled components">
                <li><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
                <?php
                    // Some menu items are only displayed based on the user permissions level
                    if ($_SESSION['Access'] == 1) {showNonITMenu();
                    } elseif ($_SESSION['Access'] == 2) {showITSupportMenu();
                    } elseif ($_SESSION['Access'] == 3) {showITManagerMenu();}
                ?>
                <li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
            </ul>
        </nav>

        <!-- 
            Here is the page content and mobile menu bar. The mobile bar is only visible when the screen
            size is smaller. The main navbar from above will not be displayed as well.
        -->
        <div id="content">

            <!-- Mobile navbar (this is only intended for non-IT Support users) -->
            <nav class="d-block d-md-none navbar navbar-expand-lg navbar-dark">
                <div class="container-fluid">
                    <span class="navbar-brand mb-2 h1">Support Ticket System</span>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ml-auto">
                                <li><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
                                <li><a href="create_ticket.php"><i class="fa fa-ticket" aria-hidden="true"></i> Create Ticket</a></li>
                                <li><a href="my_tickets.php"><i class="fa fa-tags" aria-hidden="true"></i> My Tickets</a></li>
                                <li><a href="my_profile.php"><i class="fa fa-address-card" aria-hidden="true"></i> My Profile</a></li>
                                <li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
                            </ul>
                        </div>
                </div>
            </nav>
            
            <h2>Create a new ticket</h2><hr>

                <form method="post">
                <div class="form-group col-lg-6">
                    <label for="priority">Select ticket priority:</label>
                    <select class="custom-select" name="priority" id="priority" required>
                        <option value="">Choose...</option>
                        <option value="High">High (within 1 business day)</option>
                        <option value="Medium">Medium (within 2-3 business days)</option>
                        <option value="Low">Low (within 4-7 business days)</option>
                        </select>
                </div>
                <div class="form-group col-lg-6">
                    <label for="title">Ticket Title:</label>
                    <input type="text" class="form-control" id="title" name="title" required></textarea>
                </div>
                <div class="form-group col-lg-6">
                    <label for="description">Ticket description:</label>
                    <textarea class="form-control" id="description" rows="10" name="description" required></textarea>
                </div>
                <div class="col-auto">
                    <input type="submit" class="btn btn-info" name="submit_ticket" value="Submit New Ticket">
                </div>
                </form>
        </div>
    </div>

    <!-- Latest stable version of jQuery (required for Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

 </body>
</html>