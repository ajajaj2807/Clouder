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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Clouder || The Cloud Network</title>
    <meta charset="UTF-8">
    <meta name="author" content="Shubham Mishra">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="feed.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>
<body>
    <nav class="navbar">
    <span class="logo">Clouder</span>
    <div class="nav">
      <span class="greet">Hello, <?php echo $name;?> !</span><br>
      <a class="logout-btn" href="logout.php">Log Out</a>
    </div>
    </nav>

    <div class="head-post">
        <p>NOTE: Regular HTML, CSS and Bootstrap formatting is allowed.<br> Site is still vulnerable to XSS.</p>
        <textarea class="form-control" placeholder="Write a new post!" height=40 width=60 id="posttxt"></textarea><br>
        <div class="btn" id="postbtn">Post</div><br><br>

        <div id="postmsg" class="alert" style="display: none;"></div>
        
        <script>
            $('document').ready(function(){
                $('#postbtn').click(function(){
                    $.post("post.php", {postcontent: $('#posttxt').val()}, function(response, status){
                        if (response == "Ok"){
                            $('#postmsg').show();
                            $('#postmsg').removeClass('alert-danger alert-success');
                            $('#postmsg').addClass('alert-success');
                            $('#postmsg').html("Posted Successfully! Please refresh the page to view changes.");
                            $('#postmsg').fadeOut(3000);
                        }else{
                            $('#postmsg').show();
                            $('#postmsg').removeClass('alert-danger alert-success');
                            $('#postmsg').addClass('alert-danger');
                            $('#postmsg').html("Oops! Something went wrong. Please try again!");
                            $('#postmsg').fadeOut(3000);
                        }
                    });
                });
            });
        </script>
    </div>

    <div class="post-row" id="feed">
            <?php
                $q = "SELECT MAX(Id) FROM post";
                $rs = mysqli_query($conn, $q);
                $row = mysqli_fetch_assoc($rs);
                $_SESSION["feed_pos"] = $row["MAX(Id)"];

                $q = "SELECT link FROM post ORDER BY Id DESC LIMIT 10";
                $rs = mysqli_query($conn, $q);

                while ($row = mysqli_fetch_assoc($rs)){
                    $fp = fopen($row["link"], "r") or die("error");
                    echo fgets($fp);
                    echo "</div></div>";
                    fclose($fp);
                }
            ?>

    </div>
    
                        
</body>
</html>
