
<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "schoolrecords";

// CREATE CONNECTION
$conn = new mysqli($servername, $username, $password, $database);

// CHECK CONNECTION
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";


// READ
if ($_SERVER['REQUEST_METHOD'] == "GET") {

    // TO CHECK IF THE SEARCH (SchoolID) IS PROVIDED.
    if(isset($_GET['SchoolID'])) {
        $SchoolID = $_GET['SchoolID'];

        // TO SELECT RECORDS BY USING THE SEARCH TERM: SchoolID.
        $sql = "SELECT * FROM schoolrecords WHERE SchoolID = '$SchoolID'";

    } else {
        // TO SELECT ALL RECORDS IF THE SEARCH TERM (SchoolID) IS NOT PROVIDED.
        $sql = "SELECT * FROM schoolrecords";
    }

    $result = $conn->query($sql);
    echo "GET";

    if ($result->num_rows > 0) {
        // TO FETCH THE DATA.
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo "No records found";
    }
}



// CREATE 
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // TO CHECK IF ALL REQUIRED FIELDS ARE PRESENT.
    if (isset($data['SchoolID'], $data['FirstName'], $data['LastName'], $data['MiddleInitial'], 
              $data['DateofBirth'], $data['Gender'], $data['Course'], $data['YearLevel'])) {

        // TO EXTRACT DATA FROM $data ARRAY.
        $SchoolID = $data['SchoolID'];
        $FirstName = $data['FirstName'];
        $LastName = $data['LastName'];
        $MiddleInitial = $data['MiddleInitial'];
        $DateofBirth = $data['DateofBirth'];
        $Gender = $data['Gender'];
        $Course = $data['Course'];
        $YearLevel = $data['YearLevel'];

        // TO INSERT VALUES INTO THE TABLE.
        $sql = "INSERT INTO schoolrecords (SchoolID, FirstName, LastName, MiddleInitial, DateofBirth, Gender, Course, YearLevel) 
                VALUES ('$SchoolID', '$FirstName', '$LastName', '$MiddleInitial', '$DateofBirth', '$Gender', '$Course', '$YearLevel')";
 
        $message = new stdClass();

        if ($result = $conn->query($sql) === TRUE) {
            $message->successful = "true";
            $message->message = "User Created Successfully";
            echo json_encode($message, JSON_PRETTY_PRINT);
        } else {
            $message->successful = "false";
            $message->message = "User Already Exists!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }
}



// UPDATE
if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // TO CHECK IF ALL REQUIRED FIELDS ARE PRESENT.
    if (isset($data['SchoolID'], $data['FirstName'], $data['LastName'], $data['MiddleInitial'], 
              $data['DateofBirth'], $data['Gender'], $data['Course'], $data['YearLevel'])) {

        // TO EXTRACT DATA FROM $data ARRAY.
        $SchoolID = $data['SchoolID'];
        $updatedFirstName = $data['FirstName'];
        $updatedLastName = $data['LastName'];
        $updatedMiddleInitial = $data['MiddleInitial'];
        $updatedDateofBirth = $data['DateofBirth'];
        $updatedGender = $data['Gender'];
        $updatedCourse = $data['Course'];
        $updatedYearLevel = $data['YearLevel'];

        
        $sql = "UPDATE schoolrecords SET 
                    FirstName='$updatedFirstName', 
                    LastName='$updatedLastName', 
                    MiddleInitial='$updatedMiddleInitial', 
                    DateofBirth='$updatedDateofBirth', 
                    Gender='$updatedGender', 
                    Course='$updatedCourse', 
                    YearLevel='$updatedYearLevel' 
                WHERE SchoolID='$SchoolID'";

        $message = new stdClass();

        // TO EXECUTE SQL QUERY AND HANDLE ERRORS.
        if ($conn->query($sql) === TRUE) {
            $message->successful = "true";
            $message->message = "Record Updated Successfully";
            echo json_encode($message, JSON_PRETTY_PRINT);
        } else {
            $message->successful = "false";
            $message->message = "Error updating record: " . $conn->error;
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    } else {
        $message = new stdClass();
        $message->successful = "false";
        $message->message = "Missing required fields for update";
        echo json_encode($message, JSON_PRETTY_PRINT);
    }
}




// DELETE 
if ($_SERVER['REQUEST_METHOD'] == "DELETE" && isset($_GET['SchoolID'])) {
    $idToDelete = $_GET['SchoolID'];

    // TO EXECUTE A DELETE QUERY
    $sql = "DELETE FROM schoolrecords WHERE SchoolID='$idToDelete'";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

?>