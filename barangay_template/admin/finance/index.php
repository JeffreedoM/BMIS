<?php
include '../../includes/deactivated.inc.php';
include '../../includes/session.inc.php';
include './includes/connect.php';


$clearance = $pdo->query("SELECT * FROM clearance")->fetchAll();

// JOIN TABLES 

$joint = $pdo->query("SELECT * FROM clearance_release cr
                    JOIN clearance c ON cr.clearance_id = c.clearance_id
                    JOIN resident r ON cr.resident_id = r.resident_id")->fetchAll(); 


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/main.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="./assets/css/buttons.css" type="text/css" />
    
    <title>Admin Panel</title>
</head>

<body>
    <?php
    include '../../partials/nav_sidebar.php';
    ?>

    <main class="main-content">
        <?php
        include '../../partials/nav_header.php';
        ?>

        <!-- Container -->
        <div class="wrapper">
            <!-- Page header -->
            <!-- This is where the title of the page is shown -->
            <div class="page-header">
                <h3 class="page-title">List of Transactions</h3>
            </div>

            <!-- Page body -->
            <div class="page-body" style="overflow-x:auto; min-height: 60vh;">
                

                <div class="add_clearance">
                    <a href="clearance-transactions.php">
                    <button type="submit" class="btn"><span>View Transactions per Form</span></button>
                    </a>
                </div>
                <!-- List of clearances -->
                <div>
                    <table id="clearance-list" class="row-border hover">
                        <thead>
                            <tr>
                                <th>Form Type</th>
                                <th>Given To</th>
                                <th>Amount</th>
                                <th>Date Given</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($joint as $row) { ?>
                            <tr>
                                   
                                    <td><?php echo $row['clearance_name']?></td>
                                    <td><?php echo $row['firstname']; echo ' ' . $row['middlename']; echo ' ' .$row['lastname']?></td>
                                    <td><?php echo "₱" . $row['clearance_amount'];?></td>
                                    <td><?php echo $row['date']?></td>
                            </tr>
                                    <?php } ?>
                        </tbody>
                    </table>
                </div>
                
            </div>

            <!-- Add clearance pop-up -->
            <div class="add-clearance" id="modal" >
                
                <div class="content" id="popup">
                <button class="closebtn"onclick="closePopup()">X</button>
                    <h1 style="margin-bottom: 1rem;">Clearance Name/Type:</h1>
                    <form action="" method="POST" required>
                        <!-- input clearance name/type -->
                        <div>
                            <p></p>
                            <input type="text" name="clearancename" id="clearancename" placeholder="" required>
                        </div>
                        
                        <button type="submit" name="submit" id="submitButton" class="submitButton" >Add Clearance</button>
                    </form>
                </div>
            </div>
            
    </main>

    <script src="../../assets/js/sidebar.js"></script>
    <script src="../../assets/js/header.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready( function () {
    $('#clearance-list').DataTable();
    } );
    </script>
    <!-- popup js -->
        <script>
            let popup = document.getElementById("popup")
            let modal = document.getElementById("modal")
           
           
                function openPopup() {
                    modal.classList.add("modal-active");
                    popup.classList.add("open-popup");
                } 
                function closePopup() {
                    popup.classList.remove("open-popup");
                    modal.classList.remove("modal-active");
                }             
        </script>

    <!-- event listener 
        <script>
            const submitButton = document.getElementById("submitButton")
                
            submitButton.addEventListener("click", function(event) {
                event.preventDefault();
            } );

            if (validateForm()) {
                
            }


    //input field checking 
            function validateForm() {
                let clearancename = document.getElementById("clearancename")

                if (clearancename == null || clearancename == "") {
                    alert("Clearance name must be filled out");
                    return false;
                }

                return true;
            }        
        </script> -->
    



    

        
</body>

</html>