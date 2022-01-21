<?php

// Available command line options (directives):
$allDirectives =  array(
    "u"=>"-u – MySQL username",
    "p"=>"-p – MySQL password",
    "h"=>"-h – MySQL host",
    "file" => "--file [csv file name] – this is the name of the CSV to be parsed",
    "create_table" => "--create_table – this will cause the MySQL users table to be built (and no further action will be taken)",
    "dry_run" => "--dry_run – this will be used with the --file directive in case we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered",
    "help" => "--help – which will output the above list of directives with details.\n\n"
);

//INPUT

$shortOptions = "u:p:h:";
$longOptions  = array(
    "file:",
    "create_table",
    "dry_run",
    "help",
);

$db = 'catalyst';
$inputOptionArr = getopt($shortOptions , $longOptions);
$inputOptionArrKeys = array_keys($inputOptionArr);
$insertDbFlag = array_key_exists("dry_run",$inputOptionArr);

if (count($inputOptionArr) < 1) {
    echo "Wrong command line options OR parameters/command have not been provided. Use --help \n";
}
if (array_key_exists("help",$inputOptionArr))
{    foreach ($allDirectives as $key => $value){
        echo $value."\n";
    }
}

if (array_key_exists("dry_run",$inputOptionArr)){
    if(!array_key_exists("file",$inputOptionArr)){
        echo "--dry_run should be user with --file directive! \n";
    }
}


if (isset($inputOptionArr['u']) OR isset($inputOptionArr['p'])){
    if (count(array_intersect($inputOptionArrKeys, array("h", "p", "u"))) >= 3){
        echo "trying to connection to the db \n";
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Set MySQLi to throw exceptions
        try {
            $mysqli = mysqli_connect($inputOptionArr['h'], $inputOptionArr['u'],$inputOptionArr['p'], $db);
        } catch (mysqli_sql_exception $e) {
            die("Unfortunately, the details you entered for connection are incorrect! \n");
        }
        if($mysqli) {
            echo "Connection is successful \n";
        }
    } else {
        echo "Cant connect to the db. User, password or host missing \n";
    }
}


if (isset($inputOptionArr['file'])){
    //FILE VALIDATION
        if(fileValidation($inputOptionArr['file'])){
            if (isset($mysqli)){
            try {
                $sql = "SELECT * FROM users";
                if (mysqli_query($mysqli, $sql) == TRUE) {
                    $open = fopen($inputOptionArr['file'].".csv", "r");
                    $rowCount = 0;
                    while (($data = fgetcsv($open, 1000, ",")) !== FALSE)
                    {
                        $rowCount++;
                        if($rowCount == 1){
                            continue;
                        } else {
                            //VALIDATE DATA
                            if(dataValidation($data)){
                                //INSERT DATA
                                if (!$insertDbFlag){
                                    insertDataIntoUserDB($data, $mysqli);
                                }
                            }
                        }
                    }
                } else {
                    echo "User table does not exist. Use --create_table command \n";
                }

            } catch (mysqli_sql_exception $e) {
                die("Table does not exist. Use --create_table command! \n");
            }
            } else {
                echo "database connections is required in order to parse the CSV file \n";
            }
        }
}

if (isset($inputOptionArr['create_table'])){
    $sql = "CREATE TABLE users (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(30) NOT NULL,
                    surname VARCHAR(30) NOT NULL,
                    email VARCHAR(50),
                    UNIQUE KEY unique_email (email) 
                )";
    if (isset($mysqli)) {
            if (mysqli_query($mysqli, $sql) === TRUE) {
                echo "Table Users created successfully \n";
            } else {
                echo "Error creating table: " . mysqli_error($mysqli). "\n";
            }
    } else {
        echo "database connections is required in order to create user table \n";
    }
}

if (isset($mysqli)){
    mysqli_close($mysqli);
}






// FILE VALIDATION
function fileValidation($file){
    if(file_exists($file.".csv")) {
        if (($open = fopen($file.".csv", "r")) !== FALSE)
        {
            if (($data = fgetcsv($open, 1000, ",")) !== FALSE)
            {
                if($data[0]!= "name" OR $data[1]!= "surname" OR $data[2]!= "email"){
                    echo "Columns in the file should be as following: name, surname, email\n";
                    fclose($open);
                    return false;
                } else {
                    echo $file." is a valid file\n";
                    return true;
                }
            } else {
                echo "Error: File is empty.\n";
                return false;
            }
        } else {
            echo "Error: Permission to open this file denied.\n";
            return false;
        }
    } else {
        echo "Error: The file does not exist.\n";
        return false;
    }
}

//FILE DATA VALIDATION
function dataValidation($data){
    $name = ucwords(strtolower($data[0]));
    $surname = ucwords(strtolower($data[1]));
    $email = strtolower($data[2]);
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo $name. " ". $surname. " ".$email." valid \n";
        return true;
    } else {
        echo $name. " ". $surname. " did not provide a valid email: ".$email."\n";
        return false;
    }
}

// INSERT DATA TO DB
function insertDataIntoUserDB($data, $mysqli){
    $name = ucwords(strtolower($data[0]));
    $surname = ucwords(strtolower($data[1]));
    $email = strtolower($data[2]);
    $stmt = mysqli_prepare($mysqli, "INSERT INTO users (name, surname, email) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt ,"sss", $name, $surname, $email);
    if(!mysqli_stmt_execute($stmt)){
        echo "Error inserting into table: " . mysqli_error($mysqli)."\n";
    }
    mysqli_stmt_close($stmt);
}













