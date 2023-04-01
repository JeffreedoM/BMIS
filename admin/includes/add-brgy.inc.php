<?php

include 'dbh.inc.php';
define('SITE_ROOT', realpath(dirname(__FILE__)));

if (isset($_POST['check_submit_btn'])) {
    $brgyName = $_POST['brgy-name'];

    $sql = "SELECT * FROM barangay WHERE b_name=?";
    $result = $pdo->prepare($sql);
    $result->execute([$brgyName]);
    $numRows = $result->fetchColumn();


    if (strtolower($brgyName) === 'admin') {
        echo "'admin' is not available.";
        echo "<script>$('#submit').prop('disabled', true);
        $('#submit').css('background-color', 'red');
        $('#submit').text('\'admin\' is not available. Try another one');
        </script>";
    } else if (strtolower($brgyName) === 'test') {
        echo "'test' is not available.";
        echo "<script>$('#submit').prop('disabled', true);
        $('#submit').css('background-color', 'red');
        $('#submit').text('\'test\' is not available. Try another one');
        </script>";
    } else if (strtolower($brgyName) === 'barangay_template') {
        echo "'barangay_template' is not available.";
        echo "<script>$('#submit').prop('disabled', true);
        $('#submit').css('background-color', 'red');
        $('#submit').text('\'barangay_template\' is not available. Try another one');
        </script>";
    } else if ($numRows > 0) {
        echo 'Barangay already exists';
        echo "<script>$('#submit').prop('disabled', true);
        $('#submit').css('background-color', 'red');
        $('#submit').text('Barangay already exists. Try another one');
        </script>";
    } else {
        // echo "It's Available.";
        echo "<script>$('#submit').prop('disabled', false);
        $('#submit').css('background-color', '#0062FF');
        $('#submit').hover(function(){
            $(this).css('background-color', '#0062FF');
            }, function(){
            $(this).css('background-color', '#0053D9');
          });
        $('#submit').text('Add Barangay');
        </script>";
    }
}


if (isset($_POST['submit'])) {
    $brgyName = $_POST['brgy-name'];
    $brgyAddress = $_POST['brgy-address'];
    $brgyFullAddress = "$brgyAddress Indang, Cavite";

    $brgyFolderName = strtolower(str_replace(" ", "-", trim($brgyName)));
    $brgyLink = 'indang.gov.ph/' . $brgyFolderName;

    $firstName = $_POST['firstname'];
    $middleName = $_POST['middlename'];
    $lastName = $_POST['lastname'];

    $fullName = "$firstName $middleName $lastName";

    $username = $_POST['username'];
    $password = $_POST['password'];

    // for Barangay Logo
    $file = $_FILES['image'];

    $fileName = $_FILES['image']['name'];
    $fileTmpName = $_FILES['image']['tmp_name'];
    $fileSize = $_FILES['image']['size'];
    $fileError = $_FILES['image']['error'];
    // $fileType = $_FILES['image']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    // if the file extenstion is allowed
    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            //if there is no error uploading the file
            if ($fileSize < 5000000) {  // 5mb
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                // $fileDestination = 'assets/images/uploads/barangay-logos/' . $fileNameNew;
                $fileDestination =  'assets/images/uploads/barangay-logos/' . $fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);
                // file is successfully uploaded

            } else {
                //if the file size is too big
                echo "
                <script>
                    alert('File size is too large');
                </>
            <?php";
            }
        } else {
            // if there is an error uploading the file
            echo "
            <script>
                alert('There was an error uploading the file. Please try again!');
            </script>
            ";
        }
    } else {
        //if the file extension is not allowed
        echo "
        <script>
            alert('Please upload jpg, jpeg, and png files only.');
        </script>
        ";
    }

    //Creating the directory of the barangay
    $src = "../barangay_template";
    $dst = "../$brgyFolderName";

    function recursiveCopy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    recursiveCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    recursiveCopy($src, $dst);
    // $files = scandir($dst);
    // print_r($files);



    //insert barangay into database if the directory is created
    if (is_dir($dst)) {

        //Inserting barangay
        $sql = "INSERT INTO barangay (b_name, b_address, b_logo, b_link, is_active) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$brgyName, $brgyFullAddress, $fileNameNew, $brgyLink, 1]);

        //Insert brgy admin as 1st resident
        $insertAdmin = $pdo->prepare("INSERT INTO resident (barangay_id, firstname, middlename, lastname) VALUES (?,?,?,?)");
        $insertAdmin->execute([$pdo->lastInsertId(), $firstName, $middleName, $lastName]);

        //Insert login credentials of the brgy. admin in accounts table
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $admin_account = $pdo->prepare("INSERT INTO accounts (resident_id, username, password, position) VALUES (?,?,?,?)");
        $admin_account->execute([$pdo->lastInsertId(), $username, $hashed_password, 'Barangay Secretary']);
    }
}
