<!DOCTYPE html>
<html>

<head>
    <title>Update Courses</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Public Sans' rel='stylesheet'>
    <link rel="stylesheet" href="/sub_sites/Update_Course/style/style.css">
    <link rel="stylesheet" href="/sub_sites/Update_Course/style/navbar.css">
    <link rel="icon" type="image/x-icon" href="/data/images/crestFavicon.ico">
    <script type="text/javascript" src="/sub_sites/Update_Course/js/index.js"></script>
</head>

<body>
    <div class="navbar">
        <button class="neumorphic" onclick="location.href='/sub_sites/Tree/'"><span>Tree Model</span></button>
        <button class="neumorphic" onclick="location.href='/sub_sites/Documentation'"><span>API Documentation</span></button>
        <button class="neumorphic" onclick="location.href='/'"><span>Home</span></button>
        <button class="neumorphic" onclick="location.href='/sub_sites/Course_Searcher/index.php'"><span>Course Searcher</span></button>
        <button class="neumorphic" onclick="location.href='/sub_sites/Update_Course/'"><span>Update Course</span></button>
    </div>
    <div class="body-div">
        <h1>Update Courses</h1>
        <label for="course_code">Select Course:</label>
        <input type="text" id="course_code" name="course_code" placeholder="Enter Course Code">

        <label for="course_name">Course Name:</label>
        <input type="text" id="course_name" name="course_name" placeholder="Enter Course Name">

        <label for="course_description">Course Description:</label>
        <input type="text" id="course_description" name="course_description" placeholder="Enter Course Description">

        <label for="timings">Timings:</label>
        <input type="text" id="timings" name="timings" placeholder="Enter Course Timings">

        <label for="lecture">Lecture:</label>
        <input type="text" id="lecture" name="lecture" placeholder="Enter Lecture Information">

        <label for="credits">Credits:</label>
        <input type="text" id="credits" name="credits" placeholder="Enter Course Credits">

        <label for="Authorization">Password:</label>
        <input type="password" id="password" name="Authorization" placeholder="Enter Authentication Key">

        <button type="submit" onclick="editCourseFromServer()">Update Course</button>
    </div>
</body>

</html>