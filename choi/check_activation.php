<?php
session_start();
$username="";
$enabled="";
if(isset($_SESSION["username"]) && isset($_SESSION["enabled"])){
    $username = $_SESSION["username"];
    $enabled = $_SESSION["enabled"];
}else{

    ;
}



$code = $_GET["code"];
if($username!="" && $enabled==1){
    header("Location: /home.php");
    die();
}

$status = do_Pdo($code);
function do_Pdo($code){
    $servername = "localhost";
    $username = "root";
    $password = "wkfyrnwhtlqka1";
    try{
        $conn = new PDO("mysql:host=$servername;dbname=hycube", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $conn->prepare("SELECT email FROM activation where code = :code");
        $stmt->execute(array(':code' => $code));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            $email = $result[0]["email"];
            $stmt = $conn->prepare("DELETE FROM activation WHERE code=:code");
            $stmt->execute(array(':code' => $code));
            $stmt = $conn->prepare("UPDATE student SET enabled=1 WHERE username=:email");
            $stmt->execute(array(':email' => $email));
        }else{
            return "오류 : 해당 해쉬값이 존재하지 않습니다. : ".$code;
        }
    }
    catch (PDOException $e){
        echo "doPdo failed: " . $e->getMessage();
    }
    return "success";
}
function do_job($code){
    $servername = "localhost";
    $username = "root";
    $password = "wkfyrnwhtlqka1";
    $email = "";

    $conn = new mysqli($servername, $username, $password, "hycube");
    if($conn->connect_error){

        return "db_connection error";
    }

    $sql = "SELECT email FROM activation where code = '".$code."'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $email = $row["email"];
        $sql = "DELETE FROM activation WHERE code='".$code."'";
        if ($conn->query($sql) !== TRUE) {
            return "Error: " . $sql . "<br>" . $conn->error;
        }
        $sql = "UPDATE student SET enabled=1 WHERE username='".$email."'";
        if ($conn->query($sql) !== TRUE) {
            return "Error: " . $sql . "<br>" . $conn->error;

        }

    }
    else{
        return "오류 : 해당 해쉬값이 존재하지 않습니다. : ".$code;
    }
    return "success";
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/default.css">
    <script src=/script/common.js>
    </script>
</head>
<body>
<div class="header">
    <div class="logo">
        <h1>HYCUBE</h1>
        <div class="explain web">Information Security Institute of Hanyang University</div>
        <div class="mobile mlogin">
            <img class="img_user_mobile" src="/img/user.png">
        </div>
    </div>

</div>
<div id="menu" class="menu">
    <ul class="topnav">
        <li class="mobile"><a href="/home.php" onclick="open_sidenav()">&#9776;</a></li>
        <li class="mobile"><a class="active" href="#home"><i class="fa fa-home"></i></a></li>
        <li class="mobile dropdown"><a href="#news">
                <i class="fa fa-sticky-note-o"></i>
            </a>
            <div class="dropdown-content">
                <a href="#">Notice</a>
                <a href="#">Freeboard</a>
            </div>
        </li>
        <li class="mobile"><a href="#contact"><i class="fa fa-phone"></i></a></li>
        <li class="mobile"><a href="#about"><i class="fa fa-question-circle"></i></a></li>
        <li class="web"><a href="#home" onclick="open_sidenav()">&#9776;</a></li>
        <li class="web active"><a href="/home.php">Home</a></li>
        <li class="web dropdown"><a href="#news">Board</a>
            <div class="dropdown-content">
                <a href="#">Notice</a>
                <a href="#">Freeboard</a>
            </div>
        </li>

        <li class="web"><a href="#contact">Contact</a></li>
        <li class="web"><a href="#about">About</a></li>
        <li class="web right dropdown profile">
            <a class="profile" href="#account">
                <?php if ($username != "") {
                    ?>
                    <img class="img_user" src="/img/user.png">
                    <span><?php echo $_SESSION["username"] ?></span>
                <?php } else {
                    ?>
                    <img class="img_user" src="/img/user.png">
                    <span>My account</span>
                    <!-- for debug use
                    <span id="width">w</span><span id="height" style="margin-left: 20px">h</span>
                    -->
                <?php }
                ?>
            </a>
            <?php
            if ($_SESSION["username"] != "") {
                ?>
                <div class="dropdown-content login_info">
                    <div class="login_desc">
                        <h5><?php echo $_SESSION["username"] ?></h5>
                    </div>
                    <button onclick="" class="button_small grey">My info</button>
                    <button id="logout" onclick="do_logout()" class="button_small red">Log out</button>
                </div>
                <?php
            } else {
                ?>
                <div class="dropdown-content login_no_info">
                    <div class="login_desc">
                        Login required to access this feature
                    </div>
                    <button onclick="modal('login')" class="button blue">Log in</button>
                    <button id="signup" onclick="modal('register')" class="button red">Sign up</button>
                </div>
                <?php
            }
            ?>
        </li>
    </ul>
</div>
<ul id="sidenav" class="sidenav">
    <li>
        <div id="intro">
            <h3>Winter 2016</h3>
        </div>
    </li>
    <li>
        <?php
        if ($_SESSION["username"] != "") {
            ?>
            <a onclick="do_logout()" href="#logout">
                Log out
            </a>

            <?php
        } else {
            ?>
            <a onclick="modal('login')" href="#login">
                Log in
            </a>
            <?php
        }
        ?>
    </li>
    <li><a class="active" href="#home">Home</a></li>
    <li class="application">
        <a>
            Application
            <i class="fa fa-caret-down" style="display: inline;"></i>
        </a>
        <ul class="navdrop">
            <li><a href="#notepad">notepad</a></li>
            <li><a href="#draw">draw</a></li>
        </ul>
    </li>

</ul>

<div id="container" class="container">
    <div class="activation_notice">
        <?php
        if($status==="success") {
            ?>
            <h1>Registration Complete</h1>
            <p>You are able to get all features from our website!</p><br/>
            <button class="button grey" onclick="modal('log in')">Log in</button>
            <?php
        }else{
            ?>
            <h1>Error Occured</h1>
            <p><?php echo $status ?></p>
            <br/>
            <button class="button blue" onclick='location.href="/home.php"'>Back to home</button>
            <?php
        }
        ?>
    </div>
</div>
<!-- List of Modals -->
<div id="login" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h3>User Authentication</h3>
        </div>
        <div class="modal-body">
            <form method="post" onsubmit="return do_login()">
                <label for="fname">Username</label>
                <input type="text" id="login_username" name="username" placeholder="example@hanyang.ac.kr">
                <label for="lname">Password</label>
                <input type="password" id="login_password" name="password">
                <button id="login_button" type="submit" class="button blue full">Submit</button>
            </form>
            <div class="error">
                <div class="info" style="width:5%"><i class="fa fa-warning"></i></div><div class="info" style="width:80%"></div>
            </div>
        </div>
    </div>
</div>

<div id="register" class="modal">

    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h3>Register</h3>
        </div>
        <div class="modal-body">
            <form method="post" onsubmit="return do_signup()">
                <label for="fname">Username</label>
                <input type="text" id="username" name="username" placeholder="example@hanyang.ac.kr">

                <label for="lname">Password</label>
                <input type="password" id="password" name="password">
                <button id="signup_button" type="submit" class="button blue full">Submit</button>
            </form>
            <div class="error">
                <div class="info" style="width:5%"><i class="fa fa-warning"></i></div><div class="info" style="width:80%">Please enter valid e-mail address</div>
            </div>

        </div>
    </div>
</div>
<div id="myinfo" class="modal">

    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h3>Register</h3>
        </div>
        <div class="modal-body">
            <form>
                <label for="fname">Username</label>
                <input type="text" id="username" name="username" placeholder="example@hanyang.ac.kr">
                <label for="lname">Password</label>
                <input type="password" id="password" name="password">
            </form>
            <button class="button blue full" onclick="do_modify()">Submit</button>
            <button class="button red full" onclick="do_delete()">Delete Account</button>
        </div>
    </div>
</div>
<div id="confirm" class="modal">

    <div class="modal-content" style="width: 30%">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h3>System</h3>
        </div>
        <div class="modal-body" style="text-align: center">
            <p>Activation link has been sent via your email.</p>
            <p>Please check your email address for registration.</p>
            <button class="button" onclick="modal_reset()">OK</button>
        </div>
    </div>
</div>
<div id="login_fail" class="modal">

    <div class="modal-content" style="width: 30%">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h3>System</h3>
        </div>
        <div class="modal-body" style="text-align: center">
            <p>Your username or password does not match any account.</p>
            <p>Please make sure that you have valid username and password</p>
            <button class="button" onclick="modal_reset()">OK</button>
        </div>
    </div>
</div>
<div id="activation" class="modal">

    <div class="modal-content" style="width: 50%">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h3>Activation required</h3>
        </div>
        <div class="modal-body" style="text-align: center">
            <p>Please verify your account by activation email sent to you </p>
            <p>If you have not recieved yet, you may retry by clicking 'Send e-mail' button</p>
            <button id="send_mail" class="button blue" onclick="send_mail('<?php echo $username ?>')">Send e-mail
            </button>
            <button class="button red" onclick="modal_reset()">Cancel</button>
        </div>
    </div>
</div>
<div id="footer">
    <h1>HYCUBE</h1>
    <p>경기도 안산시 상록구 한양대학로 55 한양대학교 ERICA캠퍼스 4공학관 1층 SMASH 학습전용공간</p>
</div>


<?php
if ($username != "" && $enabled == 0) {
    ?>
    <script>
        modal('activation');
    </script>
    <?php
}
?>
</body>
</html>
