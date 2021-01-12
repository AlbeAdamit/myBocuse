<?php
// session_start();
include 'secret.php';

/* Hide error from webpage
 error_reporting(0);*/

/*TODO
checking the username and password length
checking for invalid characters
verify why session doesn't retrieve data on reload => uncomment all session related code
 */

// if($_SESSION['logged_in']=='true'){
//     echo $_SESSION['logged_in'];
// };

 //check if pwd and/or email field is empty
if (((!isset($_POST['user_pwd'])) OR( !isset($_POST['user_email']))) /*AND ($_SESSION['logged_in']=='false')*/){
?>
<p>Please enter email and password</p>
<form method="post">
    <p>
        <input name="user_email" maxlength="50" />
        <input type="password" name="user_pwd" maxlength="50" />
        <input type="submit" value="Valider" />
    </p>
</form>
<?php
}else{

    $user_input_pwd=$_POST['user_pwd'];  
    // $_SESSION['logged_in']='true';
    // $_SESSION['user_pwd']=$user_input_pwd;
    
    //verify hashed password
    if (password_verify($user_input_pwd,$hash)) {
        echo 'Password is valid!';
        //if password was hashed in older version or cost increased with better hardware
        if ( password_needs_rehash ( $hash, PASSWORD_DEFAULT ) ) {
            $newHash = password_hash( $user_input_pwd, PASSWORD_DEFAULT );
    
            /* TODO
            UPDATE the user's row in `log_user` to store $newHash 
            */
          }
        
    } else {
        echo 'Invalid password.';
        $_SESSION['logged_in']='false';
    }
}



// Connect to DB 
$connect = mysqli_connect('localhost', $user, $user_input_pwd, 'mybocuse');

//check connection
if(!$connect){
echo 'Connection error:' . mysqli_connect_error();
}

if($connect){
// write query for all users

$sql ='SELECT Name, LastName, Account_Type, Photo,id FROM `user info`';

//make query & get result

$result = mysqli_query($connect, $sql);

//fetch resulting rows as array

$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

//clean result from memory

mysqli_free_result($result);

//close connection to DB
mysqli_close($connect);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyBocuse</title>
    <link rel='stylesheet' href='stylesheet.css'>
</head>

<body>

    <?php
    //Display data from $user if successfully connected to DB
    // Retrieves pertinent data from each user and displays it in it's own card
        if($connect){
    foreach($users as $user){
        ?> <h1>Users</h1>
            <div class="profile__container">
            <div class='profile__card'>
            <h2><?php echo htmlspecialchars($user['Name'].' '.$user['LastName']); ?></h2>
            <div><?php echo htmlspecialchars($user['Account_Type']); ?></div>
            <img class='profile__card--img' src='<?php echo $user['Photo'];?>' alt='hi'>
            <div class='calendar'>Calendar</div>
        </div>
        <?php }}
        // session_destroy();
        ?>
    </div>
</body>
</html>