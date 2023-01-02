<?php
include 'dbconfig.php';
include "classes/Homepage.class.php";
$title= "Homepage";
include "header.php";
$homepage = new Homepage($connection);
$homepage->checkRequest();
?>

<html>

<div id="navigation_bar">

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="./index.php">Notepad</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="./index.php">Home</a></li>
<!--                <li><a href="./notes.php">Notes</a></li>-->
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php

                if (!isset($_SESSION['logged_in'])) { ?>
                    <li><a href="./register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                    <li><a href="./index.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    <?php
                } else { ?>
                    <li><a href="./logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </nav>

</div>

<div id="login_box">

    <?php

    if (!isset($_SESSION['logged_in'])) { ?>
        <center><h1>Login</h1>
        <p>Please fill in this form to login to your account.</p></center>
        <hr>

        <?php
    }
    ?>

    <?php

    if (!isset($_SESSION['logged_in'])) {?>
   <center>
       <form action="" method="post">

        <label for="email"><b>Email Address:</b></label>
        <input type="text" placeholder="Enter Email" name="email" required>

        <label for="pw"><b>Password:</b></label>
        <input type="password" placeholder="Enter Password" name="pw" required>

        <button type="submit" class="loginbtn" name="submit">Login</button>

    </form>
   </center>

        <?php
        echo $homepage->getLoginRejectionMessage();
    } else {?>

        <form method="post">
            <center>
                <form>
                    <select name="notes">
                        <option value="1">Note 1</option>
                        <option value="2">Note 2</option>
                        <option value="3">Note 3</option>
                    </select>
                </form>
                <br><br><button type="submit" name="loadNotes">Load Note</button>

                <br><br><label va>You are updating note: <?php echo str_replace("text", "", $_SESSION['note_index']) ?></label>
                <br><label va>Title:</label><br>
                <input type="text" id="subject" name="subject" value="<?php echo $homepage->getNotes()->getNoteSubject() ?>" style="width: 713px;">

                <br><label>Note:</label><br>
                <textarea name="textArea" rows="20" cols="100"><?php echo $homepage->getNotes()->getNoteText(); ?></textarea><br>
                <button type="submit" name="saveButton">Save Text</button>
            </center>
        </form>

    <?php } ?>

</div>

</html>