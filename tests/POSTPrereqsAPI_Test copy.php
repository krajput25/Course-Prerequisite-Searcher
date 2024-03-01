<?php
// tests/ApiEndpointGetTest.php

use PHPUnit\Framework\TestCase;

include_once 'prerequisites.php';

class POSTPrereqsAPI_Test extends TestCase
{

    public function testGetPrerequisites_ValidInput_Returns2Courses()
    {
        $body = '{"courses": ["CIS*1300"], "coursesWithCreditRequirement": true, "includeEmpty": false, "displayCodesOnly": false}';
        $output = $this->runApiEndpoint(json_decode($body));
        $this->arrayHasCourseWithCode($output, "CIS*2170");
        $this->arrayHasCourseWithCode($output, "CIS*2500");
    }

    public function testGetPrerequisites_ValidInput_Returns3Courses()
    {
        $body = '{"courses": ["CIS*1300", "CIS*1910"], "coursesWithCreditRequirement": true, "includeEmpty": false, "displayCodesOnly": false}';
        $output = $this->runApiEndpoint(json_decode($body));
        $this->arrayHasCourseWithCode($output, "CIS*2170");
        $this->arrayHasCourseWithCode($output, "CIS*2500");
        $this->arrayHasCourseWithCode($output, "CIS*2910");
    }

    public function testGetPrerequisites_ValidInput_WithOnlyPrereqCourseArray()
    {
        $body = '{"courses": ["CIS*1300", "CIS*1910"]}';
        $output = $this->runApiEndpoint(json_decode($body));
        $this->arrayHasCourseWithCode($output, "CIS*2170");
        $this->arrayHasCourseWithCode($output, "CIS*2500");
        $this->arrayHasCourseWithCode($output, "CIS*2910");
        $this->arrayHasExpectedLength($output, 3);
    }

    public function testGetPrerequisites_ValidInput_ReturnsCourseCodesOnly()
    {
        $body = '{"courses": ["CIS*1300", "CIS*1910"], "coursesWithCreditRequirement": true, "includeEmpty": false, "displayCodesOnly": true}';
        $output = $this->runApiEndpoint(json_decode($body));
        $this->arrayHasOnlyCourseCodes($output, "CIS*2170");
        $this->arrayHasOnlyCourseCodes($output, "CIS*2500");
        $this->arrayHasOnlyCourseCodes($output, "CIS*2910");
    }

    //for four courses and creditReqs == true
    public function testGetPrerequisites_ValidInput_CoursesWithCreditRequirements()
    {
        $body = '{"courses": ["CIS*1300", "CIS*1910", "CIS*2030", "CIS*2910"], "coursesWithCreditRequirement": true, "includeEmpty": false, "displayCodesOnly": true}';
        $output = $this->runApiEndpoint(json_decode($body));
        $this->arrayHasExpectedLength($output, 98);
    }
    public function testGetPrerequisites_ValidInput_InludeEmptyArrayLength()
    {
        $body = '{"courses": ["CIS*1300"], "coursesWithCreditRequirement": true, "includeEmpty": true, "displayCodesOnly": false}';
        $output = $this->runApiEndpoint(json_decode($body));
        $this->arrayHasExpectedLength($output, 204);
    }

    private function runApiEndpoint($body)
    {
        // Output of our endpoint
        $output = findPrerequisites($body);

        return $output;
    }

    private function arrayHasCourseWithCode($output, $courseCode) {
        $output_json = json_decode($output, true);
        $this->assertIsArray($output_json);
        foreach ($output_json as $courseObj) {
            if($courseObj["course_code"] == $courseCode) {
                return true;
            }
        }
        return false;
    }

    private function arrayHasOnlyCourseCodes($output, $courseCode)
    {
        $output_json = json_decode($output, true);
        $this->assertIsArray($output_json);
        foreach ($output_json as $courseObj) {
            if ($courseObj == $courseCode) {
                return true;
            }
        }
        return false;
    }

     private function arrayHasExpectedLength($output, $expectedLength) {
        $output_json = json_decode($output, true);
        $this->assertIsArray($output_json);
        $this->assertCount($expectedLength, $output_json);
    }
}


