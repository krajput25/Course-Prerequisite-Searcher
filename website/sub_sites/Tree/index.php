<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Planner</title>
    <link href='https://fonts.googleapis.com/css?family=Public Sans' rel='stylesheet'>
    <link rel="stylesheet" href="/sub_sites/Tree/style/style.css">
    <link rel="stylesheet" href="/sub_sites/Tree/style/navbar.css">
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

    <h1 class="main-heading">Course Tree Display</h1>
    <div class="inputsDiv">
        <div class="box-left">
            <div class="course-planner">
                <h1>DEPARTMENT TREE</h1>
                <p>Enter a department code to get a tree of the prerequisites</p>
                <div class="search-container">
                    <div class="search-input">
                        <input type="text" id="department" placeholder="Example: CIS, ACCT, MGMT">
                        <button id="departButton" onclick="createDepartmentGraph()">Display</button>
                    </div>
                    <div class="result_box"></div>
                </div>
            </div>
        </div>
        <div class="box-right">
                <div class="course-planner">
                    <h1>COURSE TREE</h1>
                    <p>Enter a course code to get a tree of prerequisites</p>
                    <div class="search-container">
                        <div class="search-input">
                            <input type="text"id="course" placeholder="Example: CIS*2750, ACCT*2230" >
                            <button id = "courseButton" onclick="createCourseGraph()">Display</button>
                        </div>
                        <div class="result_box2"></div>
                    </div>
                </div>
            </div>
    </div>
    <div id="prerequisitesContainer"></div>
    <script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    <script type="text/javascript" src="/sub_sites/Tree/js/index.js"></script>
</body>