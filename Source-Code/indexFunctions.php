<!--Name: Michael Cong
    Course: ICS4U Ms. Cullum
    Date: May 22nd, 2020
    Purpose: Lisgar TeCheckout - ISU
    Usage: These are the PHP functions used on the main page of the website
-->

<?php

function connectDB(){          
    //specifies all the necessary variables    
    $host = "localhost";
    $username = "root";
    $user_pass = "usbw";
    $database_in_use = "database_1";

    //This is the OOP style usage of the connect to DB command.
    global $mysqli;
    $mysqli = new mysqli($host, $username, $user_pass, $database_in_use);

    //Error message if something goes wrong
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

    //host information displayed in top left corner. (should read "localhost via TCP/IP)
    echo $mysqli->host_info;
}

//This function takes some parameters then adds a new entry to the MySQL Database.
function addEntry($mysqli, $submittedDeviceID, $submittedName, $submittedPeriodNum){
    $sql = "INSERT INTO signoutrecord_table(laptopID, studentName, periodNumber)
            VALUES('$submittedDeviceID', '$submittedName', '$submittedPeriodNum')";

    //If the SQL operation executes succesfully, a JS alert box will pop up, informing the user as such. 
    //Otherwise, a different one will pop up and declare to the user that something went wrong.
    if($mysqli->query($sql)){
        echo '<script type="text/javascript">';
        echo 'window.alert("Entry has been recorded succesfully.")';
        echo '</script>';
    }else{
        echo '<script type="text/javascript">';
        echo 'window.alert("Something went wrong. Please verify the connection with the MySQL database.")';
        echo '</script>';       
    }

    $mysqli->close();
}
?>