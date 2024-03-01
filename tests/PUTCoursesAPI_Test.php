<?php
use PHPUnit\Framework\TestCase;

include_once 'courses.php';

class PUTCoursesAPI_Test extends TestCase
{
    public function testUpdateCourse_ValidInput_Returns2Courses()
    {
        $_SERVER["HTTP_AUTHORIZATION"] = "abc123";
        $course_code = "CIS*3760";
        $body = '{"course_name": "Best course evah!", "course_description": "This is a course that teaches you a lot of stuff and things!",
            "timings": "SOMESTUFF", "lecture": "LEC:420;LAB:69", "credits": "1.0"}';
        $output = $this->runApiEndpoint($body, $course_code);
        $this->assertTrue(isset($output["message"]), "Output did not have a message");
        $this->assertEquals(http_response_code(), 200, "HTTP Response code was incorrect");
        $output = $this->getCourseEndpoint($course_code);
        $this->assertEquals(http_response_code(), 200, "HTTP Response code was incorrect");
        $this->assertEquals($course_code, $output["course_code"], "The course code did not match expectations!");
        $this->assertEquals("Best course evah!", $output["course_name"], "The course name did not match expectations!");
        $this->assertEquals("Fall and Winter", $output["terms"], "The terms did not match expectations!");
        $this->assertEquals("SOMESTUFF", $output["timings"], "The timings did not match expectations!");
        $this->assertEquals("LEC:420;LAB:69", $output["lecture"], "The lecture timings did not match expectations!");
        $this->assertEquals($output["credits"], "1", "The credit amount did not match expectations!");
    }

    public function testUpdateCourse_InvalidCourseCode_ReturnsError()
    {
        $_SERVER["HTTP_AUTHORIZATION"] = "abc123";
        $course_code = "CIS*12345678";
        $body = '{"course_name": "Best course evah!", "course_description": "This is a course that teaches you a lot of stuff and things!",
            "timings": "SOMESTUFF", "lecture": "LEC:420;LAB:69", "credits": "1.0"}';
        $output = $this->runApiEndpoint($body, $course_code);
        $this->assertTrue(isset($output["message"]), "Output did not have a message");
        $this->assertEquals(http_response_code(), 400, "HTTP Response code was incorrect");
    }

    public function testUpdateCourse_SQLInjectionAttempt_Fails()
    {
        $_SERVER["HTTP_AUTHORIZATION"] = "abc123";
        $course_code = "CIS*3760";
        $body = '{"course_name": "this is a name!", "course_description": "This is a course that teaches you a lot of stuff and things!",
            "timings": "SOMESTUFF", "lecture": "LEC:420;LAB:69", "credits": "1.0; DELETE FROM testing_db WHERE course_code = \'CIS*1300\';"}';
        $output = $this->runApiEndpoint($body, $course_code);
        $this->assertTrue(isset($output["message"]), "Output did not have a message");
        $this->assertEquals(http_response_code(), 200, "HTTP Response code was incorrect");
        $output = $this->getCourseEndpoint($course_code);
        $this->assertEquals(http_response_code(), 200, "HTTP Response code was incorrect");
        $this->assertEquals('1', $output["credits"], "Error verifying credits!");
        $output = $this->getCourseEndpoint("CIS*1300");
        $this->assertEquals('CIS*1300', $output["course_code"], "Course got deleted from injection attack!");
        $this->assertEquals(http_response_code(), 200, "HTTP Response code was incorrect");
    }

    private function runApiEndpoint($body, $course_code)
    {
        $_GET["id"] = $course_code;
        $output = updateCourseInformation($body);
        return json_decode($output, true);
    }

    private function getCourseEndpoint($courseCode)
    {
        $_GET["id"] = $courseCode;
        $output = getCourseData();
        return json_decode($output, true);
    }
}


