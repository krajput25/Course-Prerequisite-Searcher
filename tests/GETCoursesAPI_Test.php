<?php
// tests/ApiEndpointGetTest.php

use PHPUnit\Framework\TestCase;
include_once 'courses.php';

class GETCoursesAPI_TEST extends TestCase {

    public function testGetCourseData_ValidInput_ReturnsCourseData() {
        $_GET["id"] = "CIS*1300";
        $output = $this->runApiEndpoint();
        $this->assertValidCourseData($output);
    }

    public function testGetCourseData_InvalidInput_ReturnsError() {
        $_GET["id"] = "INVALID_CODE";
        $output = $this->runApiEndpoint();
        $this->assertInvalidCourseData($output);
    }

    public function testGetCourseData_EmptyInput_ReturnsError() {
        $_GET["id"] = "";
        $output = $this->runApiEndpoint();
        $this->assertInvalidCourseData($output);
    }

    public function testGetAllCourses_ReturnsArrayOfSpecificLength() {
        unset($_GET["id"]); // "id is not set"
        $output = $this->runApiEndpoint();
        $this->assertArrayOfSpecificLength($output, 1915);
    }

    private function runApiEndpoint() {
        // Output of our endpoint
        $output = getCourseData();

        return $output;
    }

    private function assertValidCourseData($output) {
        $output_json = json_decode($output, true);
        $this->assertArrayHasKey("course_code", $output_json);
        $this->assertArrayHasKey("course_name", $output_json);
        $this->assertArrayHasKey("department", $output_json);
        $this->assertArrayHasKey("credits", $output_json);

        // Assertions for specific values
        $this->assertEquals("CIS*1300", $output_json["course_code"]);
        $this->assertEquals("Programming", $output_json["course_name"]); 
        $this->assertEquals("School of Computer Science", $output_json["department"]); 
        $this->assertIsNumeric($output_json["credits"]); // Assuming credits should be a numeric value
        $this->assertGreaterThanOrEqual(0, $output_json["credits"]); // Assuming credits should be a non-negative value
    }

    private function assertInvalidCourseData($output) {
        $output_json = json_decode($output, true);

        // Assert that the output does not contain certain keys
        $this->assertArrayNotHasKey("course_code", $output_json);
        $this->assertArrayNotHasKey("course_name", $output_json);
        $this->assertArrayNotHasKey("department", $output_json);
        $this->assertArrayNotHasKey("credits", $output_json);

        // Additional assertions for invalid data
        $errors = [];

        if (isset($output_json["course_name"])) {
            // Assert that the course name is not empty or null
            if (empty($output_json["course_name"])) {
                $errors[] = "Course name cannot be empty.";
            }
        }

        if (isset($output_json["department"])) {
            // Assert that the department is not empty or null
            if (empty($output_json["department"])) {
                $errors[] = "Department cannot be empty.";
            }

            // Assert that the department is a string
            if (!is_string($output_json["department"])) {
                $errors[] = "Department should be a string.";
            }
        }

        if (isset($output_json["credits"])) {
            // Assert that the credits is numeric
            if (!is_numeric($output_json["credits"])) {
                $errors[] = "Credits should be numeric.";
            }

            // Assert that the credits is greater than or equal to 0
            if ($output_json["credits"] < 0) {
                $errors[] = "Credits should be greater than or equal to 0.";
            }
        }

        if (!empty($errors)) {
            $errorMessage = "Invalid course data:\n" . implode("\n", $errors);
            throw new \PHPUnit\Framework\AssertionFailedError($errorMessage);
        }
    }

    private function assertArrayOfSpecificLength($output, $expectedLength) {
        $output_json = json_decode($output, true);

        // Assert that the output is an array
        $this->assertIsArray($output_json);

        // Assert that the array has the specific length
        $this->assertCount($expectedLength, $output_json);
    }
}

