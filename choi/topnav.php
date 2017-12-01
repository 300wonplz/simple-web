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