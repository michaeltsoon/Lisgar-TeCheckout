<!--Name: Michael Cong
    Course: ICS4U Ms. Cullum
    Date: May 22nd, 2020
    Purpose: Lisgar TeCheckout - ISU
    Usage: These are the PHP functions that are used in the Control Panel page
-->
<?php
    //this function connects to the mysql database
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

    //as its name suggests, this function takes the search result as a passed parameter and displays the table using HTML
    function printTable($result){
        if ($result->num_rows > 0) {
            echo "<div class='tableWrapper'>";
            echo '<table id="table1">';
            echo "<tr><th>Time Stamp</th><th>Laptop ID</th><th>Student Name</th><th>Period #</th></tr>";
            
            //prints each row, one by one.
            while($row = $result->fetch_assoc()) {
                echo "<tr>"; 
                echo "<td>".$row['timeStamp']."</td>";
                echo "<td>".$row['laptopID']."</td>";
                echo "<td>".$row['studentName']."</td>";
                echo "<td>".$row['periodNumber']."</td>";
                echo "</tr>";  
            }
            echo "</table>";
            echo "</div>";

        //if there are no rows matching the search (or the table is empty), then an alert window will pop up. (implemented w/ javascript)
        } else {?>
            <html>
            <script>
                window.alert("No such entries were found in the database, or it is empty. Please verify then try again.");
            </script>
            </html>
            <?php
        }
    }

    //this function selects every row in the table, then calls the printTable function to display them on-screen
    function displayAll($mysqli){
        $sql = "SELECT timeStamp, laptopID, studentName, periodNumber FROM signoutrecord_table";
        $result = $mysqli->query($sql);
        printTable($result);
        $mysqli->close();
    }

    //this function finds all the entries where the studentName variable includes the passed parameter $keywordName
    function nameSearch($mysqli, $keywordName){ 
        $sql = "SELECT timeStamp, laptopID, studentName, periodNumber FROM signoutrecord_table WHERE studentName LIKE '%". $keywordName ."%'";
        $result = $mysqli->query($sql);
        printTable($result);
        $mysqli->close();
    }

    //Same as above, except this function searches for an exact laptop ID
    function idSearch($mysqli, $keywordID){
        $sql = "SELECT timeStamp, laptopID, studentName, periodNumber FROM signoutrecord_table WHERE laptopID = $keywordID";
        $result = $mysqli->query($sql);
        printTable($result);
        $mysqli->close();
    }

    //Same as above. This time there are two conditions though. (The period # must be correct, and the timestamp must be from the specified date)
    function timeSearch($mysqli, $keywordDate, $keywordPeriod){
        //If it is 5, that means that entries from the entire day must be shown. 
        if($keywordPeriod == 5){
            $sql = "SELECT timeStamp, laptopID, studentName, periodNumber FROM signoutrecord_table 
            WHERE timeStamp LIKE '%". $keywordDate ."%'";
        } else{
            $sql = "SELECT timeStamp, laptopID, studentName, periodNumber FROM signoutrecord_table 
            WHERE periodNumber = $keywordPeriod AND timeStamp LIKE '%". $keywordDate ."%'";
        }
        $result = $mysqli->query($sql);
        printTable($result);
        $mysqli->close();
    }

    //uses an SQL operation (Truncate Table) which quickly removes all data from a table
    function deleteAll($mysqli){
        $query = "TRUNCATE TABLE `signoutrecord_table`";
        $result = mysqli_query($mysqli, $query);
        $mysqli->close();
    }

    //This function will only delete entries where the timestamp includes the specified date (passed into this function as a parameter)
    function deleteDate($mysqli, $keywordDelete){
        $query = "DELETE FROM signoutrecord_table WHERE timestamp LIKE '%". $keywordDelete ."%'";
        $result = mysqli_query($mysqli, $query);
        $mysqli->close();
    }
?>