<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Planner</title>
    <link href='https://fonts.googleapis.com/css?family=Public Sans' rel='stylesheet'>
    <link rel="stylesheet" href="/sub_sites/Course_Searcher/style/course_searcher.css">
    <link rel="stylesheet" href="/sub_sites/Course_Searcher/style/navbar.css">
    <link rel="icon" type="image/x-icon" href="/data/images/crestFavicon.ico">
</head>

<body>
    <div class="navbar">
        <button class="neumorphic" onclick="location.href='/sub_sites/Tree/'"><span>Tree Model</span></button>
        <button class="neumorphic" onclick="location.href='/sub_sites/Documentation'"><span>API Documentation</span></button>
        <button class="neumorphic" onclick="location.href='/'"><span>Home</span></button>
        <button class="neumorphic" onclick="location.href='/sub_sites/Course_Searcher/index.php'"><span>Course Searcher</span></button>
        <button class="neumorphic" onclick="location.href='/sub_sites/Update_Course/'"><span>Update Course</span></button>
    </div>

    <div class="container">
        <div class="box-left">
            <div class="course-planner">
                <h1>COURSE PLANNER</h1>
                <p>Use our course planner to help you identify the courses you need to take to ensure you're on schedule to
                    graduate. Simply enter your completed courses and press search to see all upcoming classes available to help
                    you finish your degree.</p>
                <div class="search-container">
                    <div class="search-input">
                        <input type="text" id="courseCode" autocomplete="off" placeholder="Enter your courses, separated by commas or spaces..." onkeyup="capitalizeInput()">
                        <button id="submitButton">Search</button>
                    </div>
                    <div class="result_box"></div>
                </div>
                <!-- <div id="output"></div> -->
                <!-- Adding filter options -->
                <div class="filter-options">
                    <div>
                        <label for="creditRequirementsCheckbox">
                            <input type="checkbox" id="creditRequirementsCheckbox"> Show courses with credit requirements
                        </label>
                    </div>
                    <div>
                        <label for="noPrerequisitesCheckbox">
                            <input type="checkbox" id="noPrerequisitesCheckbox"> Show courses with no prerequisites
                        </label>
                    </div>
                    <div>
                        <label for="codesOnlyCheckbox">
                            <input type="checkbox" id="codesOnlyCheckbox"> Show course codes only
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-right">
            <div class="course-planner">
                <h1>SEARCH COURSE</h1>
                <p>Use our course searcher to help you easily access and identify details about any course. Simply enter a single course code
                    and press search to view all of the information. Or search and view all courses in a department by checking the search by department box and inputting a department code.</p>
                <div class="search-container">
                    <div class="search-input">
                        <input type="text" id="searchCourseCode" autocomplete="off" placeholder="Enter a code..." onkeyup="capitalizeInput()">
                        <button id="searchButton">Search</button>
                    </div>
                    <div class="result_box2"></div>
                </div>
                <div class="filter-options">
                    <div>
                        <label for="searchByDepartmentCheckbox">
                            <input type="checkbox" id="searchByDepartmentCheckbox"> Search by department
                        </label>
                    </div>
                </div>
                <div id="output"></div>
            </div>
        </div>
    </div>
    <div id="prerequisitesContainer"></div>
    <script type="text/javascript" src="/sub_sites/Course_Searcher/js/apiCalls.js"></script>
    <script type="text/javascript" src="/sub_sites/Course_Searcher/js/index.js"></script>
</body>