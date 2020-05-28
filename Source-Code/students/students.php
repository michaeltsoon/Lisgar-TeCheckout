<!--Name: Michael Cong
    Course: ICS4U Ms. Cullum
    Date: May 22nd, 2020
    Purpose: Lisgar TeCheckout - ISU
    Usage: This is the main .php file for the students page (where they can view laptop availability)
-->

<!DOCTYPE html>
<html>
    <head>
        <!--Title & link to the .css file-->
        <title>Students</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="studentsStyle.css">
    </head>

    <!--Same background image as the other pages, within a wrapper-->
    <style id="wrapper">
    	body {
    		background-image: url('../images/alternateBackground.jpg');
    	}
    </style>
    <body>
        <header>
            <h1 class="page-title">Laptop Availability</h1>
        </header>

        <nav>
            <!--These are the links to the other pages of this website (in a list format)-->
            <ul>
                <li><a href="../index.php"><b><u>Home</u></b></a></li>
                <li><a href="../students/students.php"><b><u>Students</u></b></a></li>
                <li><a href="../setup/setup.php"><b><u>Teachers</u></b></a></li>
                <li><a href="../information/information.html"><b><u>About This Website</u></a></a></li>
            </ul>
        </nav>

        <!--This is an image that adds some flavor to the page. Nothing that's overly special about it.-->
        <img id = interestingImage src="../images/students.jpg" alt="Electronic devices">

        <!--The HTML form to submit the request for laptop availability-->
        <form method="post">
            <input type="submit" class="button" id="button1" name="submitButton" value="View Laptop Availability">
            <select class="textbox" id="textbox1" name="selectPeriod">
                <option value="1">Period #1</option>
                <option value="2">Period #2</option>
                <option value="3">Period #3</option>
                <option value="4">Period #4</option>
            </select>
        </form>

        <!--PHP if statement that calls the according function if the button is pressed-->
        <?php
            include './studentsFunctions.php';
            connectDB();

            if(isset($_POST['submitButton'])){
                $keywordPeriod = $_POST['selectPeriod'];
                idSearch($mysqli, $keywordPeriod);
            }
        ?>

        <!--Obligatory footer that may or may not be legal. Nothing has actually been copyrighted.-->
        <div id="footer">
            Copyright &copy; 2020 Michael Cong
        </div>
    </body>
</html>