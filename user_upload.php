<?php

// Available command line options (directives):
$allDirectives =  array(
    "u"=>"-u – MySQL username",
    "p"=>"-p – MySQL password",
    "h"=>"-h – MySQL host",
    "file" => "--file [csv file name] – this is the name of the CSV to be parsed",
    "create_table" => "--create_table – this will cause the MySQL users table to be built (and no further action will be taken)",
    "dry_run" => "--dry_run – this will be used with the --file directive in case we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered",
    "help" => "--help – which will output the above list of directives with details."
);

//INPUT

$shortOptions = "u:p:h:";
$longOptions  = array(
    "file:",
    "create_table:",
    "dry_run",
    "help",
);

$inputOptionArr = getopt($shortOptions , $longOptions);
$inputOptionArrKeys = array_keys($inputOptionArr);
var_dump($inputOptionArr);

if (count($inputOptionArr) < 1) {
    echo "Wrong command line options OR have not been provided. Use --help \n";
}

if (isset($inputOptionArr['u']) OR isset($inputOptionArr['p'])){
    if (count(array_intersect($inputOptionArrKeys, array("h", "p", "u"))) >= 3){
        echo "trying to connection to the db \n";
            $conn = new mysqli($inputOptionArr['h'], $inputOptionArr['u'], $inputOptionArr['p']);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error."\n");
            }
            echo "Connected successfully \n";
    } else {
        echo "Cant connect to the db. User, password or host missing \n";
    }
}




foreach ($inputOptionArr as $key => $value){
    switch ($key){

        case "u":
            echo "user input \n";
            break;
        case "p":
            echo "password input \n";
            break;
        case "h":
            echo "host input \n";
            break;
    }
}





// INPUT VALIDATION FUNCTION

// FILE VALIDATION

//FILE DATA VALIDATION

// INSERT DATA TO DB