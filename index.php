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
                <a class="navbar-brand" href="./index.php">Notepad Application</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="./index.php">Home</a></li> <!-- TODO Whilst on the homepage when you click Home it no longer shows the -->
                <li><a href="./notes.php">Notes</a></li> <!-- TODO What you see on the main page is what you will see in notes.php and the main page will change-->
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (!isset($_SESSION['logged_in'])) {
                    ?>
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

<div id="login_box_container">
    <?php
    if (!isset($_SESSION['logged_in'])) {
        ?>
        <h1>Login</h1>
        <p>Please fill in this form to login to your account.</p>
        <hr>
        <?php
    }
    ?>
    <?php
    if (!isset($_SESSION['logged_in'])) {?>
        <div id="login_box">
            <form action="" method="post">

                <label for="email"><b>Email Address</b></label>
                <input type="text" placeholder="Enter Email Address Here" name="email" required>

                <label for="pw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password Here" name="pw" required>

                <button type="submit" class="btn" name="submit">Login</button>
            </form>
        </div>
        <?php
        echo $homepage->getLoginRejectionMessage();
    } else {
        ?>
        <div id="logged_in_login_box">
            <label>Please select the note you would like to load below</label>
            <form method="post">
                <select name="notes">
                        <option value="1">Note 1</option>
                        <option value="2">Note 2</option>
                        <option value="3">Note 3</option>
                    </select>
                <button type="submit" name="loadNotes" class="custom_load_notes">Load Note</button
                <label>You are currently editing Note <?php echo str_replace("text", "", $_SESSION['note_index']) ?>!</label>
                <label class="custom_note_subject">Note Subject:</label>
                <input type="text" id="subject" name="subject" value="<?php echo $_SESSION['note_subject'] ?>">

                <label class="custom_text">The details to your note</label>
                <textarea name="textArea" rows="20" cols="100"><?php echo $_SESSION['note_text'] ?></textarea>
                <button type="submit" name="saveButton" class="custom_save_text">Save Text</button>
            </form>
        </div>
    <?php } ?>
</div>
</html>