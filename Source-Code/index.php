<!--Name: Michael Cong
    Course: ICS4U Ms. Cullum
    Date: May 22nd, 2020
    Purpose: Lisgar TeCheckout - ISU
    Usage: This is the main .php file for the home page of the website
-->
<!DOCTYPE html>
<html>
    <head>
        <!--Page title, character set, links to CSS file-->
        <title>Lisgar TeCheckout</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <!-- Background image, there is a wrapper so as to better style it -->
    <style id="wrapper">
    	body {
    		background-image: url('images/background.jpg');
    	}
    </style>

    <body>
        <?php
            include './indexFunctions.php';
            connectDB();

            //If the "Submit" button is pressed, the following code executes
            if(isset($_POST['submitButton'])){
                //extracts the variables from the HTML form elements
                $submittedName = $_POST['enterName']; 
                $submittedPeriodNum = $_POST['enterPeriodNum'];
                $submittedDeviceID = $_POST['enterDeviceID'];
                
                //The addEntry function will only be called if neither fields have been left blank.
                //Note that the MySQL database has been told to automatically include the current timestamp
                if($submittedName != "" && $submittedDeviceID != ""){
                    addEntry($mysqli, $submittedDeviceID, $submittedName, $submittedPeriodNum);
                }
                else{
                    echo '<script type="text/javascript">';
                    echo 'window.alert("At least one of the fields have been left blank. Please make sure to fill them out if you wish to submit the signout form.")';
                    echo '</script>';       
                }
            }
        ?>

        <!--Self-explanatory page title-->
        <header>
            <h1 class="page-title">Lisgar TeCheckout</h1>
        </header>
        <!--An image that does some interesting things. See CSS file-->
        <img id="icon1" src="images/icon1.png" alt="Technology in the learning environment">
        <nav>
            <!--These are the links to the other pages of this website (in a list format)-->
            <ul>
                <li><a href="index.php"><b><u>Home</u></b></a></li>
                <li><a href="students/students.php"><b><u>Students</u></b></a></li>
                <li><a href="setup/setup.php"><b><u>Teachers</u></b></a></li>
                <li><a href="information/information.html"><b><u>About This Website</u></a></a></li>
            </ul>
        </nav>
        
        <!--These are the roman numeral icons that make the page look more aesthetically appealing-->
        <img class="romanNumeral" id="roman1" src="images/i.png" alt="I">
        <img class="romanNumeral" id="roman2" src="images/ii.png" alt="II">
        <img class="romanNumeral" id="roman3" src="images/iii.png" alt="III">
        <img class="romanNumeral" id="roman4" src="images/iv.png" alt="IV">
        
        <!--The labels to the boxes that the user will have to fill out in order to signout a laptop-->
        <p class="question" id="question1">Welcome! To start off, please enter your name:</p>
        <p class="question" id="question2">Please select your period number:</p>
        <p class="question" id="question3">Lastly, please enter the device # that you are signing out:</p>
        <p class="question" id="question4">When ready, click the "submit" button.</p>

        <form method="post">
            <input type="text" name="enterName" class="textbox" id="textbox1" placeholder="Your name">
            <select type="select" name="enterPeriodNum" class="textbox" id="textbox2">
                <option value="1">Period #1</option>
                <option value="2">Period #2</option>
                <option value="3">Period #3</option>
                <option value="4">Period #4</option>
            </select>
            <input type="number" name="enterDeviceID" class="textbox" id="textbox3" placeholder="Laptop/Device #">
            <input type="submit" name="submitButton" id="specialButton" value="Submit">
        </form>
        
        <!--There is a calendar icon and the date in the bottom-->
        <p id="dateDisplay">Today's date: <span id="datetime"></span></p>
        <img id="calendar" src="images/calendar.png" alt="Calendar here">
        <!--Implemented using javascript-->
        <script>
        	var dt = new Date();
        	document.getElementById("datetime").innerHTML = dt.toLocaleDateString();
        </script>

        <!--Copyright notice that probably isn't legal-->
        <div id="footer">
            Copyright &copy; 2020 Michael Cong
        </div>
    </body>
</html>