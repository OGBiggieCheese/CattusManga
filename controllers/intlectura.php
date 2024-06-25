<?php
require_once "../server/config.php";

require_once "../server/check_user_logged.php";
$manga_id = $_GET['manga'];

if (isset($_SESSION['datos'])) {
    $verifyLike = "SELECT * FROM mangalikes WHERE  manga_id= '" . $_GET['manga'] . "' AND user_id = " . $_SESSION['datos']['ID'] . " ";

    $resultVerify = mysqli_query($conn, $verifyLike);
    if (!$resultVerify) {
        echo "Error de consulta" . mysqli_error($conn);
        exit();
    }
    $rowVerify = mysqli_fetch_assoc($resultVerify);

    $verifyFav = "SELECT favourite_manga_ID FROM userprofile WHERE favourite_manga_ID= '". $_GET['manga'] . "' AND User_ID = ".$_SESSION['datos']['ID'] . "";

    $resultVerifyFav = mysqli_query($conn, $verifyFav);
    if(!$resultVerifyFav){
        die ("Error de consulta: " . mysqli_error($conn));
    }
    $rowVerifyFav = mysqli_fetch_assoc($resultVerifyFav);
//    $numVerifyFav = mysqli_num_rows($resultVerifyFav);
}

$view = "intlectura";
require_once "../views/layoutnf.php";

if(isset($_SESSION['datos'])){
   
    $uniqueviews = "SELECT * FROM manga_unique_view WHERE User_ID =" . $_SESSION['datos']['ID'] . " AND manga_ID =". $_GET['manga'] .";";
    $resView = mysqli_query($conn, $uniqueviews);
    $count=mysqli_num_rows($resView);

 if($count == 0 || $count == NULL){
    $instview= "INSERT INTO manga_unique_view (User_ID, manga_ID) VALUES(" . $_SESSION['datos']['ID']. ",". $_GET['manga'] . ");";
    $resIns = mysqli_query($conn, $instview);

    $addview = "UPDATE mangaviews SET views_count = views_count + 1 WHERE manga_ID = " . $_GET['manga'] . ";";
    $addres = mysqli_query($conn, $addview);

 
 }
  }
