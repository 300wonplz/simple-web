<?php
session_start();
/**
 * Created by PhpStorm.
 * User: chou6
 * Date: 2017-07-27
 * Time: 오전 2:11
 */
$name = $_POST["username"];
$pwd = $_POST["password"];

do_job($name, $pwd);
exit();
function doPdo($name, $pwd){
    $servername = "localhost";
    $username = "root";
    $password = "wkfyrnwhtlqka1";
    try{
        $conn = new PDO("mysql:host=$servername;dbname=hycube", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $conn->prepare("SELECT username, enabled FROM student where username = :name and password = :pwd");
        $stmt->execute(array(':name' => $name, ':pwd'=>$pwd));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            $_SESSION["username"] = $result[0]["username"];
            $_SESSION["enabled"] = $result[0]["enabled"];
        }else{
            echo "no";
            return;
        }

    }catch(PDOException $e)
    {
        echo "doPdo failed: " . $e->getMessage();

    }
    echo "success";
}
function do_job($name, $pwd){
    $servername = "localhost";
    $username = "root";
    $password = "wkfyrnwhtlqka1";
    $conn = new mysqli($servername, $username, $password, "hycube");
    if($conn->connect_error){
        echo "db_connection error";
        return;
    }
    $sql = "SELECT username, enabled FROM student where username = '".$name."' and password = '".$pwd."'";
    $result = $conn->query($sql);
    if($result->num_rows>0){
        $row = $result->fetch_assoc();

        $_SESSION["username"] = $row["username"];
        $_SESSION["enabled"] = $row["enabled"];

    }else{
        echo "no";
        return;
    }
    echo "success";
}
function test($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>