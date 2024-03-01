<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');

class CourseCredits
{
    public $dept;
    public $courseLevel;
    public $creditValue;

    function __construct($courseCode, $creditValue)
    {
        $courseSplit = explode("*", $courseCode);
        $this->dept = $courseSplit[0];
        // // Get the first digit of the course code, multiply by 1000 for course level
        $this->courseLevel = ((int)$courseSplit[1][0]) * 1000;
        $this->creditValue = (float)$creditValue;
    }
}

function meetsCreditRequirement($requirement, $creditsObj)
{
    if (($requirement['credit_type'] == "[]" || empty($requirement['credit_type'])) && empty($requirement['course_level'])) {
        return true;
    }

    // There is more than just "X credits"...
    $requiredDepts = explode(",", trim($requirement['credit_type']));
    $creditsLeft = (float)$requirement['credit_amount'];

    foreach ($creditsObj as $obj) {
        if (in_array($obj->dept, $requiredDepts)) {
            $creditsLeft -= $obj->creditValue;
        }
    }
    return $creditsLeft <= 0;
}

function meetsCourseRequirement($requirement, $coursesTaken)
{
    return in_array($requirement['condition_code'], $coursesTaken);
}

function meetsIndividualRequirement($requirement, $coursesTaken, $creditsObj)
{
    $creditRequirement = empty($requirement['condition_code']);
    $courseRequirement = !$creditRequirement;

    // Check if course requirement is met
    if ($courseRequirement && meetsCourseRequirement($requirement, $coursesTaken)) {
        return true;
    } else if ($creditRequirement && meetsCreditRequirement($requirement, $creditsObj)) {
        return true;
    }

    return false;
}

function courseCanBeTaken($requirementsNeedingMet, $coursesTaken, $creditsObj)
{
    // Don't show courses that they have already taken.
    if (in_array($requirementsNeedingMet[0], $coursesTaken)) {
        return false;
    }

    // Get all groups that need matched
    $groups = array();
    foreach ($requirementsNeedingMet as $requirement) {
        if (!in_array($requirement['course_group'], $groups)) {
            $groups[] = $requirement['course_group'];
        }
    }

    // Go through the requirements again... find a requirement in each group that matches
    $numRequirementsNeedingMet = 0;
    foreach ($requirementsNeedingMet as $requirement) {
        // Make the assumption that the only place where the number_in_group will change is when subgroup is 1
        if ($requirement['course_subgroup'] == 1) {
            // Can't have "0 requirements that need met"
            $numRequirementsNeedingMet = $requirement['multiple_in_group'] == 0 ? 1 : $requirement['multiple_in_group'];
        }

        if (meetsIndividualRequirement($requirement, $coursesTaken, $creditsObj)) {
            $numRequirementsNeedingMet--;
        }

        // If you have met all requirements that are needed for this group, delete it from the array.
        if ($numRequirementsNeedingMet == 0) {
            if (array_search($requirement['course_group'], $groups) !== FALSE) {
                unset($groups[array_search($requirement['course_group'], $groups)]);
            }
        }
    }

    return count($groups) == 0;
}

function getCourseCreditInformation($courses, $mysqli) {
    $query = "SELECT * FROM courses WHERE course_code = ?";
    $creditsObjs = array();
    foreach ($courses as $course) {
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $course);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $obj = $result->fetch_assoc();

            $creditsObjs[] = new CourseCredits($obj['course_code'], $obj['credits']);
        }

        $stmt->close();
    }
    return $creditsObjs;
}

function getRequirementsMet($body, $courses, $mysqli, $totalCredits, $creditsObjs) {
    $requirementsMet = array();
    if (isset($body->coursesWithCreditRequirement) && $body->coursesWithCreditRequirement == true) {
        $query = "SELECT * FROM prerequisites WHERE credit_amount > 0 AND credit_amount <= ?";
        $coursesArr = array();
        $stmt = $mysqli->prepare($query);

        $stmt->bind_param("s", $totalCredits);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $coursesArr[] = $row;
            }
        }

        $stmt->close();

        foreach ($coursesArr as $course) {
            if (meetsCreditRequirement($course, $creditsObjs)) {
                $requirementsMet[] = $course['course_code'];
            }
        }
    }

    $query = "SELECT * FROM prerequisites WHERE condition_code = ?";
    foreach ($courses as $course) {
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $course);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $requirementsMet[] = $row['course_code'];
            }
        }

    }

    return $requirementsMet;
}

function getEmptyPrerequisites($mysqli, $body) {
    if (!(isset($body->includeEmpty) && $body->includeEmpty == true)) 
    {
        return array();
    }

    $emptyPrerequisites = array();

    $query = "SELECT * FROM courses WHERE prerequisites = '' OR prerequisites = NULL";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $emptyPrerequisites[] = $row;
        }
    }
    $stmt->close();
    return $emptyPrerequisites;
}

function getCoursesThatCanBeTaken($requirementsMet, $mysqli, $courses, $creditsObjs) {
    $coursesThatCanBeTaken = array();
    foreach ($requirementsMet as $courseCode) {
        $prerequisiteQuery = "SELECT * FROM prerequisites WHERE course_code = ?";

        // Fetch all rows for the current course code where the requirement is met
        $prerequisiteRowsMatched = array();
        $stmt = $mysqli->prepare($prerequisiteQuery);
        $stmt->bind_param("s", $courseCode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $prerequisiteRowsMatched[] = $row;
            }
        }
        $stmt->close();

        if (courseCanBeTaken($prerequisiteRowsMatched, $courses, $creditsObjs) && !in_array($courseCode, $courses)) {
            $coursesThatCanBeTaken[] = $courseCode;
        }
    }
    return $coursesThatCanBeTaken;
}

function getCoursesAsJson($coursesThatCanBeTaken, $mysqli, $emptyPrerequisites, $body) {
    $coursesDetails = array();
    if (count($coursesThatCanBeTaken) > 0) {
        $inQuery = str_repeat('?,', count($coursesThatCanBeTaken) - 1) . '?';
        $sql = "SELECT * FROM courses WHERE course_code IN ($inQuery)";
        $stmt = $mysqli->prepare($sql);
        $types = str_repeat('s', count($coursesThatCanBeTaken));
        $stmt->bind_param($types, ...$coursesThatCanBeTaken);
        $stmt->execute();
        $result = $stmt->get_result();
        $coursesDetails = $result->fetch_all(MYSQLI_ASSOC);
    }

    $allCourses = array_merge($coursesDetails, $emptyPrerequisites);
    if (isset($body->displayCodesOnly) && $body->displayCodesOnly == true) {
        $courseCodes = array();
        foreach ($allCourses as $course) {
            $courseCodes[] = $course['course_code'];
        }
        return json_encode($courseCodes);
    } else {
        return json_encode($allCourses);
    }
}

function findPrerequisites($body) {
    $emptyPrerequisites = array();

    // Get the courses from the body
    $courses = $body->courses;

    $mysqli = new mysqli("localhost", "db_user", "db_pass", "course_db");

    if ($mysqli->connect_errno) {
        http_response_code(500);
        return json_encode(array("message" => "DB Connection Failure"), JSON_PRETTY_PRINT);
    }
    
    $creditsObjs = getCourseCreditInformation($courses, $mysqli);

    $totalCredits = 0.0;
    foreach ($creditsObjs as $courseCredit) {
        $totalCredits += $courseCredit->creditValue;
    }

    $requirementsMet = getRequirementsMet($body, $courses, $mysqli, $totalCredits, $creditsObjs);

    $emptyPrerequisites = getEmptyPrerequisites($mysqli, $body);

    $coursesThatCanBeTaken = getCoursesThatCanBeTaken($requirementsMet, $mysqli, $courses, $creditsObjs);

    $outputStr = getCoursesAsJson($coursesThatCanBeTaken, $mysqli, $emptyPrerequisites, $body);

    $mysqli->close();

    return $outputStr;
}

// Gets all prerequisites objects from a single department
function getPrerequisitesObjsFromDept($mysqli, $department) {
    // Gets all of the course codes in the department. 
    $department = $department . "%";

    $query = "SELECT * FROM prerequisites WHERE course_code LIKE ?";

    $departmentCourses = array();
    // If you weren't able to prepare the statement
    if (!($stmt = $mysqli->prepare($query))) {
        http_response_code(500);
        $mysqli->close();
        return json_encode(array("message" => "Database error"), JSON_PRETTY_PRINT);
    }
    // Bind the parameter to the statement
    $stmt->bind_param("s", $department);

    // If the statement can not be executed, throw a 500
    if (!$stmt->execute()) {
        http_response_code(500);
        $mysqli->close();
        return json_encode(array("message" => "Execution error"), JSON_PRETTY_PRINT);
    }

    // Get the results, since there was many. 
    $result = $stmt->get_result();

    // If no results were found...
    if ($result->num_rows <= 0) {
        http_response_code(404);
        $mysqli->close();
        $stmt->close();
        return json_encode(array("message" => "Courses in department not found"), JSON_PRETTY_PRINT);
    }
    // Fetch the associated objects, put into $departmentCourses
    while ($row = $result->fetch_assoc()) {
        $departmentCourses[] = $row;
    }
    $mysqli->close();
    $stmt->close();
    return $departmentCourses;
}

function getPrerequisitesForCourse($mysqli, $courseCode, &$processedCourses = [], &$treeViewObj = null){

    if (is_null($treeViewObj)) {
        $treeViewObj = array();
        $treeViewObj["course_codes"] = array();
        $treeViewObj["edges"] = array();
    }

    if (array_key_exists($courseCode, $processedCourses)){
        return;
    }

    $processedCourses[$courseCode] = [];

    $query = "SELECT * FROM prerequisites WHERE course_code = ?";

    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        http_response_code(500);
        $mysqli->close();
        return json_encode(array("message" => "Database error"), JSON_PRETTY_PRINT);
    }

    $stmt->bind_param("s", $courseCode);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $conditionCode = $row["condition_code"];
        $processedCourses[$courseCode][] = $conditionCode;

        if (!in_array($courseCode, $treeViewObj["course_codes"]) && $courseCode != "") {
            array_push($treeViewObj["course_codes"], $courseCode);
        }

        if (!in_array($conditionCode, $treeViewObj["course_codes"]) && $conditionCode != "") {
            array_push($treeViewObj["course_codes"], $conditionCode);
        }

        if ($conditionCode != "") {
            array_push($treeViewObj["edges"], array("course" => $courseCode, "hasPrerequisite" => $conditionCode));
        }

        getPrerequisitesForCourse($mysqli, $conditionCode, $processedCourses, $treeViewObj);
    }

    $stmt->close();

    return json_encode($treeViewObj);
}

function getPrerequisitesForDepartment($mysqli, $department){

    $departObjects = getPrerequisitesObjsFromDept($mysqli, $department);

    // Threw an error message, return fail and die
    if (!is_array($departObjects)) {
        http_response_code(404);
        return json_encode(array("message" => "Could not find department!"));
    }

    $treeViewObj = array();
    $treeViewObj["course_codes"] = array();
    $treeViewObj["edges"] = array();

    foreach ($departObjects as $course) {
        if (!preg_match("/^([A-Z]){2,4}\*[\d]{4}$/", $course["condition_code"])) {
            if ($course["condition_code"] != "") {
                error_log("Error occurred parsing prerequisites, continuing past the error.");
                continue;
            }
        }

        // If this is a prerequisite involving a course (e.g. don't parse prerequisites that are credit-based)
        if ($course["condition_code"] != "") {
            array_push($treeViewObj["edges"], array("course" => $course["course_code"], "hasPrerequisite" => $course["condition_code"]));
            if (in_array($course["condition_code"], $treeViewObj["course_codes"]) == false) {
                array_push($treeViewObj["course_codes"], $course["condition_code"]);
            }
            if (in_array($course["course_code"], $treeViewObj["course_codes"]) == false) {
                array_push($treeViewObj["course_codes"], $course["course_code"]);
            }
        }
    }

    return json_encode($treeViewObj);
}

function getPrerequisites() {
    $mysqli = new mysqli("localhost", "db_user", "db_pass", "course_db");
    
    if ($mysqli->connect_errno) {
        http_response_code(500);
        return json_encode(array("message" => "Failed to connect to MySQL: " . $mysqli->connect_error), JSON_PRETTY_PRINT);
    }

    if (isset($_GET["dept"])) {
        $department = $_GET["dept"];
        return getPrerequisitesForDepartment($mysqli, $department);

    } elseif (isset($_GET["id"])) {
        $course = $_GET["id"];
        return getPrerequisitesForCourse($mysqli, $course);

    } else {
        return json_encode(array("message" => "Please provide a department or course code!"), JSON_PRETTY_PRINT);
    }

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    assert_true(file_get_contents('php://input') != '', json_encode(array("message" => "No body given!")));

    $body = json_decode(file_get_contents('php://input'));
    assert_true((isset($body->courses) && count($body->courses) > 0) || 
               (isset($body->includeEmpty) && is_bool($body->includeEmpty) && $body->includeEmpty == true), 
                json_encode(array("message" => "Either courses or includeEmpty must be set.")));

    echo findPrerequisites($body);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    echo getPrerequisites();
}

function assert_true($value, $message) {
    if ($value == false) {
        http_response_code(400);
        echo $message; 
        die();
    }
}
