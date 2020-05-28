<!--Name: Michael Cong
    Course: ICS4U Ms. Cullum
    Date: May 22nd, 2020
    Purpose: Lisgar TeCheckout - ISU
    Usage: These are the PHP functions used on the page for students
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
        echo '<p style="position:absolute; bottom:0px;">'.$mysqli->host_info.'</p>';
    }

    //as its name suggests, this function takes the search result as a passed parameter and displays the table using HTML
    function printTable($result){

        if ($result->num_rows > 0) {
            echo "<div class='tableWrapper'>";
            echo "<table id='table1'>";
            echo "<tr><th>Laptop ID</th><th>Signed Out By</th></tr>";
            echo '<tr><td colspan="2">The following laptops are unavilable at this time</td></tr>';
            
            //prints each row, one by one.
            while($row = $result->fetch_assoc()) {
                echo "<tr>"; 
                echo "<td>".$row['laptopID']."</td>";
                echo "<td><i>".$row['studentName']."</i></td>";
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

    //This functions searches for laptop availability 
    function idSearch($mysqli, $keywordPeriod){
        date_default_timezone_set('America/Toronto'); //Changes the timezone from UTC(default) to Eastern Time
        $currentDate = date('Y-m-d'); //gets the current date
        $sql = "SELECT laptopID, studentName FROM signoutrecord_table WHERE periodNumber = $keywordPeriod AND timeStamp LIKE '%". $currentDate ."%'";
        $result = $mysqli->query($sql);
        printTable($result);
        $mysqli->close();
    }
?>