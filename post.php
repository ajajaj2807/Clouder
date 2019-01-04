<?php
session_start();
if (!isset($_SESSION["login"]) || !isset($_COOKIE["username"])){
    header("Location:index.php");
}

$conn = mysqli_connect("localhost", "root", "", "userbase");
if (!$conn){
    die(mysqli_connect_error());
}
$usr = $_COOKIE["username"];
$q = "SELECT name FROM profile WHERE username='".$usr."';";
$rs = mysqli_query($conn, $q);
while ($row = mysqli_fetch_assoc($rs)){
    $name = $row["name"];
}

$q = "SELECT MAX(Id) FROM post";
$rs = mysqli_query($conn, $q);
$row = mysqli_fetch_assoc($rs);
$postnum = (int)$row["MAX(Id)"] + 1;

$postfile = "posts/".$postnum.".txt";
$fp = fopen($postfile, "w") or die("Error1");
fwrite($fp, "<div class='post-main'><p>".date("r")."</p><br><p class='post-u'><a href='#'> ".$name." </a> posted:</p><div class='post-content'>".$_POST["postcontent"]."</div></div>");
fclose($fp);

$q = "INSERT INTO post(origin, created, link) VALUES ('".$_COOKIE["username"]."','".date("r")."','".$postfile."');";
$rs = mysqli_query($conn, $q) or die("Error2");
echo "Ok";
?>
