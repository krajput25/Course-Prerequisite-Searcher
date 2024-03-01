<?php
// tests/ApiEndpointGetTest.php

use PHPUnit\Framework\TestCase;

require 'prerequisites.php';

class GETPrereqsAPI_Test extends TestCase
{
    public function testGetPrerequisitesTree_ValidDepartment_ReturnsExpectedResult()
    {
        $_GET["dept"] = "COOP";
        $output = json_decode($this->runApiEndpoint(), true);
        $this->arrayHasCourseWithCode($output["course_codes"], "COOP*1100");
        $this->arrayHasCourseWithCode($output["course_codes"], "COOP*5000");
        $this->arrayHasExpectedLength($output["course_codes"], 6);
        $this->arrayHasExpectedLength($output["edges"], 5);
        $this->assertEquals(http_response_code(), 200, "HTTP Response code was incorrect");
    }

    public function testGetPrerequisites_InvalidDepartment_ReturnsError()
    {
        $_GET["dept"] = "DEPARTMENT!";
        $output = json_decode($this->runApiEndpoint(), true);
        $this->assertTrue(isset($output["message"]), "No message present when there should have been one!");
        $this->assertEquals(http_response_code(), 404, "HTTP Response code was incorrect");
    }

    public function testGetPrerequisites_NoDepartmentNoCourse_ReturnsError()
    {
        $output = json_decode($this->runApiEndpoint(), true);
        $this->assertTrue(isset($output["message"]), "No message present when there should have been one!");
        $this->assertEquals(http_response_code(), 404, "HTTP Response code was incorrect");
    }

    public function getGetPrerequisiteTree_ValidCourse_ReturnsExpectedResult()
    {
        $_GET["id"] = "COOP*3000";
        $output = json_decode($this->runApiEndpoint(), true);
        $this->arrayHasCourseWithCode($output["course_codes"], "COOP*1100");
        $this->arrayHasCourseWithCode($output["course_codes"], "COOP*3000");
        $this->arrayHasExpectedLength($output["course_codes"], 4);
        $this->arrayHasExpectedLength($output["edges"], 3);
        $this->assertEquals(http_response_code(), 200, "HTTP Response code was incorrect");
    }

    public function getGetPrerequisiteTree_InvalidCourse_ReturnsNoResults()
    {
        $_GET["id"] = "COURSE*123456";
        $output = json_decode($this->runApiEndpoint(), true);
        $this->arrayHasExpectedLength($output["course_codes"], 0);
        $this->arrayHasExpectedLength($output["edges"], 0);
        $this->assertEquals(http_response_code(), 200, "HTTP Response code was incorrect");
    }

    private function runApiEndpoint()
    {
        // Output of our endpoint
        $output = getPrerequisites();

        return $output;
    }

    private function arrayHasCourseWithCode($output, $courseCode) {
        $this->assertIsArray($output);
        foreach ($output as $code) {
            if($code == $courseCode) {
                return true;
            }
        }
        return false;
    }

    private function arrayHasExpectedLength($output, $expectedLength) {
        $this->assertIsArray($output);
        $this->assertCount($expectedLength, $output);
    }
}


