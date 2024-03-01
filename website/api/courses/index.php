<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    echo getCourseData();
}

if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    $input = file_get_contents("php://input");
    if (!isset($input)) {
        http_response_code(400);
        echo json_encode(array("message" => "No body provided"), JSON_PRETTY_PRINT);
    }
    echo updateCourseInformation($input);
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    echo deleteCourse();
}

function updateCourseInformation($body) {
    // Check if the password matches
    if (!isset($_SERVER["HTTP_AUTHORIZATION"]) || $_SERVER["HTTP_AUTHORIZATION"] != "abc123") {
        http_response_code(401);
        return json_encode(array("message" => "This page is unauthorized"), JSON_PRETTY_PRINT);
      }

    $mysqli = new mysqli("localhost", "db_user", "db_pass", "course_db");

    assert($mysqli instanceof mysqli, 'Invalid MySQLi object');

    // Check connection
    if ($mysqli->connect_errno) {
        http_response_code(500);
        return json_encode(array("message" => "Failed to connect to MySQL: " . $mysqli->connect_error), JSON_PRETTY_PRINT);
    }

    $data = json_decode($body, true);

    if ($data === null) {
        http_response_code(400);
        return json_encode(array("message" => "Invalid JSON payload"), JSON_PRETTY_PRINT);
    }

    $course_code = isset($_GET['id']) ? $_GET['id'] : null;

    // Validate input using regex
    if (!$course_code || !preg_match("/^([A-Z]){2,4}\*[\d]{4}$/", $course_code)) {
        http_response_code(400);
        return json_encode(array("message" => "Invalid input for course code"), JSON_PRETTY_PRINT);
    }

    list($updateFields, $bindTypes, $bindParams) = getUpdateFields($data);

    return updateDatabaseWithNewCourseInfo($mysqli, $course_code, $updateFields, $bindTypes, $bindParams);
}

function getCourseData() {
    // Handle GET requests to retrieve course data
    $mysqli = new mysqli("localhost", "db_user", "db_pass", "course_db");

    if ($mysqli->connect_errno) {
        http_response_code(500);
        return json_encode(array("message" => "Failed to connect to MySQL: " . $mysqli->connect_error), JSON_PRETTY_PRINT);
    }

    $myArray = array();

    // Note: Implication of this is that if both ID and department are set, 
    // ID will trump department and just only give back this course's information
    if (isset($_GET["id"])) {
        return getIndividualCourseData($mysqli);
    }

    if (isset($_GET["department"])) {
        return getDepartment($mysqli);
    }

    if (isset($_GET["departmentCodes"])) {
        return getAllDepartments($mysqli);
    }

    if ($result = $mysqli->query("SELECT * FROM courses")) {
        while ($row = $result->fetch_object()) {
            $myArray[] = $row;
        }
        http_response_code(200);
        $mysqli->close();
        return json_encode($myArray, JSON_PRETTY_PRINT);
    } else {
        http_response_code(500);
        $mysqli->close();
        return json_encode(array("message" => "Database error"), JSON_PRETTY_PRINT);
    }
}


function deleteCourse() {
    // Handle DELETE requests to delete a course and its prerequisites
    $mysqli = new mysqli("localhost", "db_user", "db_pass", "course_db");

    if ($mysqli->connect_errno) {
        http_response_code(500);
        return json_encode(array("message" => "Failed to connect to MySQL: " . $mysqli->connect_error), JSON_PRETTY_PRINT);
    }

    if (isset($_GET["id"])) {
        return deleteCourseById($mysqli);
    } else {
        http_response_code(400);
        return json_encode(array("message" => "No ID provided, can not delete course."), JSON_PRETTY_PRINT);
    }
}

function getUpdateFields($data) {
    $updateFields = array();
    $bindTypes = "";
    $bindParams = array();

    if (isset($data['course_name'])) {
        $updateFields[] = "course_name = ?";
        $bindTypes .= 's';
        $bindParams[] = $data['course_name'];
    }

    if (isset($data['course_description'])) {
        $updateFields[] = "course_description = ?";
        $bindTypes .= 's';
        $bindParams[] = $data['course_description'];
    }

    if (isset($data['timings'])) {
        $updateFields[] = "timings = ?";
        $bindTypes .= 's';
        $bindParams[] = $data['timings'];
    }

    if (isset($data['lecture'])) {
        $updateFields[] = "lecture = ?";
        $bindTypes .= 's';
        $bindParams[] = $data['lecture'];
    }

    if (isset($data['credits'])) {
        $updateFields[] = "credits = ?";
        $bindTypes .= 'd'; // binding credits which is a float
        $bindParams[] = $data['credits'];
    }

    return array($updateFields, $bindTypes, $bindParams);
}

function getIndividualCourseData($mysqli) {
    $course_code = $_GET["id"];

    // Validate input using regex
    if (!preg_match("/^([A-Z]){2,4}\*[\d]{4}$/", $course_code)) {
        http_response_code(400);
        $mysqli->close();
        return json_encode(array("message" => "Invalid input for course code"), JSON_PRETTY_PRINT);
    }

    $query = "SELECT * FROM courses WHERE course_code = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $course_code);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                http_response_code(200);
                $mysqli->close();
                $stmt->close();
                return json_encode($result->fetch_object(), JSON_PRETTY_PRINT);
            } else {
                http_response_code(404);
                $mysqli->close();
                $stmt->close();
                return json_encode(array("message" => "Course not found"), JSON_PRETTY_PRINT);
            }
        } else {
            http_response_code(500);
            $mysqli->close();
            return json_encode(array("message" => "Execution error"), JSON_PRETTY_PRINT);
        }
    } else {
        http_response_code(500);
        $mysqli->close();
        return json_encode(array("message" => "Database error"), JSON_PRETTY_PRINT);
    }
}

function getDepartment($mysqli) {
    $department = $_GET["department"];
    $department = $department . "%";

    $query = "SELECT * FROM courses WHERE course_code LIKE ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $department);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $departmentCourses[] = $row;
                }
                http_response_code(200);
                $mysqli->close();
                $stmt->close();
                return json_encode($departmentCourses, JSON_PRETTY_PRINT);
            } else {
                http_response_code(404);
                $mysqli->close();
                $stmt->close();
                return json_encode(array("message" => "Course not found"), JSON_PRETTY_PRINT);
            }
        } else {
            http_response_code(500);
            $mysqli->close();
            return json_encode(array("message" => "Execution error"), JSON_PRETTY_PRINT);
        }
    } else {
        http_response_code(500);
        $mysqli->close();
        return json_encode(array("message" => "Database error"), JSON_PRETTY_PRINT);
    }
}

function getAllDepartments($mysqli) {
    $query = "SELECT DISTINCT SUBSTRING_INDEX(course_code, '*', 1) AS department_code FROM courses";

    if ($stmt = $mysqli->prepare($query)) {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $departmentCodes[] = $row;
                }
                http_response_code(200);
                $mysqli->close();
                $stmt->close();
                return json_encode($departmentCodes, JSON_PRETTY_PRINT);
            } else {
                http_response_code(404);
                $mysqli->close();
                $stmt->close();
                return json_encode(array("message" => "Course not found"), JSON_PRETTY_PRINT);
            }
        } else {
            http_response_code(500);
            $mysqli->close();
            return json_encode(array("message" => "Execution error"), JSON_PRETTY_PRINT);
        }
    } else {
        http_response_code(500);
        $mysqli->close();
        return json_encode(array("message" => "Database error"), JSON_PRETTY_PRINT);
    }
}

function updateDatabaseWithNewCourseInfo($mysqli, $course_code, $updateFields, $bindTypes, $bindParams) {
    if (!empty($updateFields)) {
        $updateQuery = "UPDATE courses SET " . implode(', ', $updateFields) . " WHERE course_code = ?";

        if ($stmt = $mysqli->prepare($updateQuery)) {
            $bindTypes .= 's'; //this is binding course_code which is a string
            $bindParams[] = $course_code;

            $stmt->bind_param($bindTypes, ...$bindParams);

            if ($stmt->execute()) {
                http_response_code(200);
                $stmt->close();
                $mysqli->close();
                return json_encode(array("message" => "Course '{$course_code}' has been updated"), JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                $stmt->close();
                $mysqli->close();
                return json_encode(array("message" => "Course update failed"), JSON_PRETTY_PRINT);
            }
        } else {
            http_response_code(500);
            $mysqli->close();
            return json_encode(array("message" => "Database error"), JSON_PRETTY_PRINT);
        }
    } else {
        http_response_code(400);
        $mysqli->close();
        return json_encode(array("message" => "No valid fields to update"), JSON_PRETTY_PRINT);
    }
}

function deleteCourseById($mysqli) {
    $course_code = $_GET["id"];

    // Validate input using regex
    if (!preg_match("/^([A-Z]){2,4}\*[\d]{4}$/", $course_code)) {
        http_response_code(400);
        return json_encode(array("message" => "Invalid input for course code"), JSON_PRETTY_PRINT);
    }

    // Delete from the "prerequisites" table
    $queryPrerequisites = "DELETE FROM prerequisites WHERE course_code = ?";

    if ($stmtPrerequisites = $mysqli->prepare($queryPrerequisites)) {
        $stmtPrerequisites->bind_param("s", $course_code);

        if (!$stmtPrerequisites->execute()) {
            http_response_code(404);
            return json_encode(array("message" => "Prerequisites deletion failed"), JSON_PRETTY_PRINT);
        }

        $stmtPrerequisites->close();
    } else {
        http_response_code(500);
        return json_encode(array("message" => "Database error"), JSON_PRETTY_PRINT);
    }

    // Delete from the "courses" table
    $queryCourses = "DELETE FROM courses WHERE course_code = ?";

    if ($stmtCourses = $mysqli->prepare($queryCourses)) {
        $stmtCourses->bind_param("s", $course_code);

        if (!$stmtCourses->execute()) {
            http_response_code(404);
            return json_encode(array("message" => "Course deletion failed"), JSON_PRETTY_PRINT);
        }

        $stmtCourses->close();
    } else {
        http_response_code(500);
        return json_encode(array("message" => "Database error"), JSON_PRETTY_PRINT);
    }

    http_response_code(200);
    return json_encode(array("message" => "Course '{$course_code}' has been deleted"), JSON_PRETTY_PRINT);
}
?>