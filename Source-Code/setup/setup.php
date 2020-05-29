<!--Name: Michael Cong
    Course: ICS4U Ms. Cullum
    Date: May 22nd, 2020
    Purpose: Lisgar TeCheckout - ISU
    Usage: This is the main .php file for the Control Panel page
-->
<!DOCTYPE html>
<html>
    <head>
        <!--Title & link to the .css file-->
        <title>Control Panel</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="setupStyle.css">
    </head>

    <!--Same background image as the other pages, within a wrapped-->
    <style id="wrapper">
    	body {
    		background-image: url('../images/background.jpg');
    	}
    </style>

    <body>
        <?php
        session_start(); //Starts the web browsing session. This will come in handy very soon.
        include './setupFunctions.php'; //Because the functions are organized in another file, which needs to be included.
        
        //username and password variables that must be entered correctly if the user wishes to use the control panel/setup page
        $username = "Administrator"; 
        $password = "LCI2021";

        //If the logout button is "submitted" (pressed), then the session is ended.
        if(isset($_POST['buttonLogout'])){
            session_destroy();
            header('Location: '.$_SERVER['PHP_SELF']);
            exit();
        }

        //"authorized" is a session variable that determines whether the user can access the control panel. The initial state is false.
        //This is because if the user has just entered the page, then he/she will need to enter the password before they can proceed.
        //Session variables do not reset upon page reload. This is why they are being used.
        if(!isset($_SESSION['authorized'])){
            $_SESSION['authorized'] = FALSE;
        }

        //"shown" is also a session variable. If it is true, then the control panel user interface is visible/activated. 
        //It is only false when there are other things (such as a table) being displayed on screen.
        //The default value is always true, unless if stated otherwise.
        $_SESSION['shown'] = TRUE;

        //The following executes if the login button is pressed (submitting the html form that contains the username/password)
        if(isset($_POST['submit'])){
            //If the user entered the correct credentials, then he/she is now "authorized", and will be able to use the control panel
            //The "authorized" variable will remain true until the browser is closed (resetting all temporary session variables such as this one)
            //Or when the user decides to press the log out button.
            if($_POST['username'] == $username && $_POST['password'] == $password){
                $_SESSION['authorized'] = TRUE;
            } else {
                //This is what happens if the login credentials were incorrect. An alert box will popped up - implemented using javascript
                echo '<script type="text/javascript">';
                echo 'window.alert("The username or password was incorrect. Please try again.")';
                echo '</script>';
            }
        }

        //This is the login interface that contains the necessary HTML textboxes and submit button. 
        //It will only run if the user is not yet authorized.
        if ($_SESSION['authorized'] == FALSE){
                ?>
                <h1 style="font-size: 24px;">You are not yet logged in. Please do so if you wish to access the control panel.</h1>
                <form method="post">
                    <label for="usernameBox" class="loginLabel" id="usernameLabel">Username:</label>
                    <input type="text" name="username" class="loginbox"id="usernameBox"/><br />
                    <label for="passwordBox" class="loginLabel" id="passwordLabel">Password:</label>
                    <input type="password" name="password" class="loginbox" id="passwordBox"/>
                    <input type='submit' name='submit' class="button" id="loginButton" value="Log in"/>
                </form><?php
        } else if($_SESSION['authorized'] == TRUE){
            //If the "authorized" variable is true, then that must mean the user has entered the correct username & password
            //They are now allowed to use the control panel.
            
            connectDB(); //connects to the mySQL database first and foremost

            //If the "Display All Entries" button is pressed, then the website will do exactly that.
            if(isset($_POST['buttonDisplayAll'])){
                $_SESSION['shown'] = FALSE;
                echo "<p class='statusMessage'> Showing all entries in the database... </p>";
                displayAll($mysqli);
            }
            
            //If the "Search by Name" button is pressed, then that will be carried out, using what the user input into the according textbox.
            if(isset($_POST['buttonSearchName'])){
                $keywordName = $_POST['searchName']; //Creates a variable, then assigns it the value of whatever the user entered into the textbox.
                
                //the function will only be called if the textbox is not empty...
                if($keywordName != ""){
                    $_SESSION['shown'] = FALSE; //Hides the user interface.
                    echo "<p class='statusMessage'> Showing all entries with student names that match/include the string - " .$keywordName." ... </p>"; //Status message displayed at the top of the screen
                    nameSearch($mysqli, $keywordName);
                }
                else{
                    echo '<script type="text/javascript">';
                    echo 'window.alert("You cannot leave the search field blank. Try again.")';
                    echo '</script>';       
                }
            }   

            //See above.
            if(isset($_POST['buttonSearchID'])){
                $keywordID = $_POST['searchID'];

                //the function will only be called if the textbox is not empty...
                if($keywordID != ""){
                    $_SESSION['shown'] = FALSE;
                    echo "<p class='statusMessage'> Showing all entries for laptop number " .$keywordID. " ... </p>";  
                    idSearch($mysqli, $keywordID);
                }
                else{
                    echo '<script type="text/javascript">';
                    echo 'window.alert("You cannot leave the search field blank. Try again.")';
                    echo '</script>';       
                }
            }
            
            //See above.
            if(isset($_POST['buttonSearchTime'])){
                $_SESSION['shown'] = FALSE;
                $keywordDate = $_POST['searchDate'];
                $keywordPeriod = $_POST['searchPeriod'];
                echo "<p class='statusMessage'> Request received. Now showing all entries from Period #" .$keywordPeriod. " on the day " .$keywordDate. "...";
                timeSearch($mysqli, $keywordDate, $keywordPeriod);
            }

            //Deletes everything from the database (truncates table) if the user presses the button. Also shows a JS alert box afterwards.
            if(isset($_POST['buttonDeleteAll'])){ 
                deleteAll($mysqli);
                ?>
                <script>
                    window.alert("The database has been wiped succesfully.");
                </script>
                <?php
            }

            //If the "Delete all entries from date" button is pressed...
            if(isset($_POST['buttonDeleteDate'])){
                $keywordDelete = $_POST['deleteDate']; //Extracts the date the user has selected and assigns that value to this new keyword variable.
                deleteDate($mysqli, $keywordDelete); //calls the function and passes to it the parameters. 
                ?>
                <!--Javascript alert box, informing the user that the job has been done-->
                <script>
                    window.alert("Entries from the selected date have been deleted succesfully.")
                </script>
                <?php
            }

            //The control panel interface is made available if the user is "authorized" to do so. (i.e. they entered the correct credentials)
            if($_SESSION['shown'] == TRUE){?>
                <div id="controlPanel">
                    <h style='font-size: 22px'>Welcome! You are currently logged in to the Administrator account. 
                    <br> Here is your control panel: </h>
                    <!--HTML buttons that should be self-explanatory. The first one displays everything, the second one logs the user out.-->
                    <form method="post">
                        <input type="submit" name="buttonDisplayAll" class="button" id="button1" value="Display All Entries" />
                        <input type="submit" name="buttonLogout" class="button" id="button5" value="Logout" />
                    </form>
                    
                    <!--Delete all entries button found here. A JS Confirm/cancel box will pop up if it is clicked.-->
                    <form method="post" onSubmit="return window.confirm('You are about to delete all entries in the database. This action is non-reversible. Are you sure that you wish to proceed?');">
                        <input type="submit" name="buttonDeleteAll" class="button" id="button6" value="Delete All" />
                    </form>
                    
                    <!--Yet another form. This one deals with searching database for entries using student name.-->
                    <form method="post">
                        <input type="submit" name="buttonSearchName" class="button" id="button2" value="Search by Student Name" />
                        <input type="text" name="searchName" class="textbox" id="textbox1" placeholder="Enter student name here" /> 
                    </form>

                    <!--This form is for searching database by date/period #-->
                    <form method="post">
                        <input type="submit" name="buttonSearchTime" class="button" id="button3" value="Search by Day/Period #" />
                        <input type="date" name="searchDate" class="textbox" id="textbox2">
                        <!--There are only 5 valid selections for the period #, because logic. (4 periods in a school day + all day option)-->
                        <select name="searchPeriod" class="textbox" id="textbox3">
                            <option value=1>Period #1</option>
                            <option value=2>Period #2</option>
                            <option value=3>Period #3</option>
                            <option value=4>Period #4</option>
                            <option value=5>All Periods</option>
                        </select>
                    </form>

                    <!--Form for when the user wants to search up a specific laptop #-->
                    <form method="post">
                        <input type="submit" name="buttonSearchID" class="button" id="button4" value="Search by Laptop ID" />
                        <input type="number" name="searchID" class="textbox" id="textbox4" placeholder="Enter laptop ID here">
                    </form>

                    <!--Button to delete all entries from a certain day. JS Confirm/cancel box pops up upon submission, as usual.-->
                    <form method="post" onSubmit="return window.confirm('You are about to delete all entries from a certain day. This action is non-reversible. Are you sure that you wish to proceed?');">
                        <input type="submit" name="buttonDeleteDate" class="button" id="button8" value="Delete All Entries from the Day of"/>
                        <input type="date" name="deleteDate" class="textbox" id="textbox5">
                    </form>
                </div>                
            <?php
            } else if($_SESSION['shown'] == FALSE){ 
                //If the control panel is hidden, then the user must be given some way to restore it.
                //A "return to control panel" button has been made for this reason.
                //If it is pressed, then the 'shown' variable is made to be true, and the UI will be back - like magic.
                if(isset($_POST['buttonShowUI'])){
                    $_SESSION['shown'] = TRUE;
                }?>

                <form method="post">
                    <input type="submit" name="buttonShowUI" class="button" id="button7" value="Return to Control Panel" />
                </form>
                <?php
            }
        }?>
        
        <!--Link that takes the user back to the home page, along with the accompanying clipart.-->
        <img src="../images/home.png" alt="Home Icon" id="homeIcon">
        <a id=homeButton href="../index.php"><b><u>Home</u></b></a>

        <!--Obligatory footer that may or may not be legal. Nothing has actually been copyrighted.-->
        <div id="footer">
            Copyright &copy; 2020 Michael Cong
        </div>
    </body>
</html>