<?php

if (empty($_POST["name"])) {
    die("Name is required");
}

if (! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

if (strlen($_POST["password"]) < 8) {
    die("Pasword must be atleast 8 characters");
}

//password character validation
if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

//password security
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO user (name, surname, email, address, password_hash) 
VALUES (?, ?, ?, ?, ?)";

$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss",
                  $_POST["name"],
                  $_POST["surname"],
                  $_POST["email"],
                  $_POST["address"],
                  $password_hash);

    if ($stmt->execute()) {

    header("Location: signup-success.html");
    exit;
                    
    } else
     {
                    
      if ($mysqli->errno === 1062) {
         die("email already taken");
             } else {
                        die($mysqli->error . " " . $mysqli->errno);
                    }
         }



 