<?php
$hostname = "localhost";
$username = "your-username";
$password = "your-password";
$database = "hbwebsite";

// Create connection
try {
    $conn = new mysqli($hostname, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
    exit();
}

function filteration($data){
    foreach ($data as $key => $value) {
        # code...
        $value = trim($value);
        $value = stripslashes($value);
        $value = strip_tags($value);      
        $value = htmlspecialchars($value);
        
        $data[$key] = $value;
    }
    return $data;
}
function selectAll($tablename) {
    $con = $GLOBALS['conn'];
    $query = "SELECT * FROM $tablename";
    $res = $con->query($query);
    return $res;
}
function select($query,$values,$datatypes){
    $con = $GLOBALS['conn'];
    # first we have to prepare the query
    if($stmt = mysqli_prepare($con,$query)){
        # second we have to bind values with query
        if(mysqli_stmt_bind_param($stmt,$datatypes,...$values)){
            # third we have to execute the query
            if(mysqli_stmt_execute($stmt)){
                # fourth we have to get the result of executed query
                $res = mysqli_stmt_get_result($stmt);
                #and lastly return the $res variable that contains all rows of the table
                mysqli_stmt_close($stmt);
                return $res;
            }else{
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Select");
            }
        }else{
            mysqli_stmt_close($stmt);
            die("Query cannot be binded - Select");
        }
    }else{
        die("Query cannot be prepared - Select");
    }
}
function update($query,$values,$datatypes){
    $con = $GLOBALS['conn'];
    # first we have to prepare the query
    if($stmt = mysqli_prepare($con,$query)){
        # second we have to bind values with query
        if(mysqli_stmt_bind_param($stmt,$datatypes,...$values)){
            # third we have to execute the query
            if(mysqli_stmt_execute($stmt)){
                # fourth we have to get the result of executed query
                $res = mysqli_stmt_affected_rows($stmt);
                #and lastly return the $res variable that contains all rows of the table
                mysqli_stmt_close($stmt);
                return $res;
            }else{
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Update");
            }
        }else{
            mysqli_stmt_close($stmt);
            die("Query cannot be binded - Update");
        }
    }else{
        die("Query cannot be prepared - Update");
    }
}
function insert($query,$values,$datatypes){
    $con = $GLOBALS['conn'];
    # first we have to prepare the query
    if($stmt = mysqli_prepare($con,$query)){
        # second we have to bind values with query
        if(mysqli_stmt_bind_param($stmt,$datatypes,...$values)){
            # third we have to execute the query
            if(mysqli_stmt_execute($stmt)){
                # fourth we have to get the result of executed query
                $res = mysqli_stmt_affected_rows($stmt);
                #and lastly return the $res variable that contains all rows of the table
                mysqli_stmt_close($stmt);
                return $res;
            }else{
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Insert");
            }
        }else{
            mysqli_stmt_close($stmt);
            die("Query cannot be binded - Insert");
        }
    }else{
        die("Query cannot be prepared - Insert");
    }
}
function delete($query, $values, $datatypes) {
    $con = $GLOBALS['conn'];
    
    // Prepare the query
    if ($stmt = mysqli_prepare($con, $query)) {
        // Bind values with the query
        if (mysqli_stmt_bind_param($stmt, $datatypes, ...$values)) {
            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                // Get the number of affected rows
                $affectedRows = mysqli_stmt_affected_rows($stmt);
                
                // Close the statement
                mysqli_stmt_close($stmt);
                
                // Return the number of affected rows
                return $affectedRows;
            } else {
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Delete");
            }
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be binded - Delete");
        }
    } else {
        die("Query cannot be prepared - Delete");
    }
}



?>
