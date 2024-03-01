<!DOCTYPE html>
<html lang="en">

<head>
  <title>Undergraduate Course Calendar API</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://fonts.googleapis.com/css?family=Public Sans' rel='stylesheet'>
  <link rel="stylesheet" href="/sub_sites/Documentation/style/style.css">
  <link rel="stylesheet" href="/sub_sites/Documentation/style/navbar.css">
  <link rel="icon" type="image/x-icon" href="/data/images/MtnFav.ico">
  <meta charset="UTF-8">
</head>

<body>
  <div class="navbar">
    <button class="neumorphic" onclick="location.href='/sub_sites/Tree/'"><span>Tree Model</span></button>
    <button class="neumorphic" onclick="location.href='/sub_sites/Documentation'"><span>API Documentation</span></button>
    <button class="neumorphic" onclick="location.href='/'"><span>Home</span></button>
    <button class="neumorphic" onclick="location.href='/sub_sites/Course_Searcher/index.php'"><span>Course Searcher</span></button>
    <button class="neumorphic" onclick="location.href='/sub_sites/Update_Course/'"><span>Update Course</span></button>
  </div>

    <body onload="onload()">
      <h1>API Documentation</h1>
      <p>This is Group 201's API for viewing, updating, and deleting courses found in the undergraduate calendar. It is
        powered by a combination of Python, SQL, NGINX, PHP.
        <br>Click on the tiles to expand/compact them.
        <br><br>It is assumed all parameters are case sensitive, but please ensure that course codes are capitalized.
      </p>

      <div class="endpointPost">
        <div class="titleDiv" onclick="toggleDropdown(this)">
          <div class="methodPost">POST</div>
          <div class="subTitle">
            <p><strong>cis3760f23-05.socs.uoguelph.ca/api/courses/prerequisites</strong></p>
          </div>
        </div>
        <div class="parameter">
          <p>Accepts a list of courses, and returns all courses that the provided courses are prerequisites to.</p>
          <strong>Parameters:</strong>
          <ul>
            <li>
              <span class="params"><span class="paramTitle">Courses</span> - Array of prerequisite courses</span>
            </li>
            <li>
              <span class="params"><span class="paramTitle">coursesWithCreditRequirement (Optional)</span> - A body
                parameter defining
                whether or not to show courses where only credit prerequisites are met. Default: false</span>
            </li>
            <li>
              <span class="params"><span class="paramTitle">includeEmpty (Optional)</span> - A body parameter defining
                whether or not
                to show courses with no prerequisites. Default: false</span>
            </li>
            <li>
              <span class="params"><span class="paramTitle">displayCodesOnly (Optional)</span> - A body parameters
                defining
                whether
                only course codes should be shown in the output. Default: false</span>
            </li>
          </ul>
          <strong>Example Usage:</strong>
          <ul>
            <li>
              <span class="params"><code>POST cis3760f23-05.socs.uoguelph.ca/api/courses/prerequisites</code></span>
            </li>
            <textarea readonly>
      {
        "courses": [
            "CIS*1300",
            "CIS*1910"
        ],
        "coursesWithCreditRequirement": false,
        "includeEmpty": false,
        "displayCodesOnly": false
      }</textarea>
            </span>
          </ul>
          <strong>Output:</strong>
          <ul>
            <pre id="prerequisiteDisplay" class="json"></pre>
          </ul>
        </div>
      </div>


      <div class="endpointGet">
        <div class="titleDiv" onclick="toggleDropdown(this)">
          <div class="methodGet">GET</div>
          <div class="subTitle">
            <p><strong>cis3760f23-05.socs.uoguelph.ca/api/courses</strong></p>
          </div>
        </div>
        <div class="parameter">
          <p>Returns all courses and their respective parameters.</p>
          <strong>Parameters:</strong>
          <ul>
            <li>
              <span class="params"><span class="paramTitle">departmentCodes</span> - Whether to get all department codes from the server</span>
            </li>
            <li>
              <span class="params"><span class="paramTitle">department</span> - Returns all courses in the department, and no others.</span>
            </li>
          </ul>
          <strong>Example Usage:</strong>
          <ul>
            <li>
              <span class="params"><code>GET cis3760f23-05.socs.uoguelph.ca/api/courses</code></span>
            </li>
          </ul>
          <strong>Output:</strong>
          <ul>
            <li>
              <span class="params">All of the courses in the database, along with their parameters.
                <br>As follows:
              </span>
            </li>

            <pre id="courseInfoDisplay" class="json"></pre>
          </ul>
        </div>
      </div>

      <div class="endpointGet">
        <div class="titleDiv" onclick="toggleDropdown(this)">
          <div class="methodGet">GET</div>
          <div class="subTitle">
            <p><strong>cis3760f23-05.socs.uoguelph.ca/api/courses/{{id}}</strong></p>
          </div>
        </div>
        <div class="parameter">
          <p>Given a course ID, returns the course associated with the course id, or an error message when there are no
            associated courses.</p>
          <strong>Parameters:</strong>
          <ul>
            <li>
              <span class="params"><span class="paramTitle">id</span> - Path parameter specifying the course code of the
                course you wish to use</span>
            </li>
          </ul>
          <strong>Example Usage:</strong>
          <ul>
            <li>
              <span class="params"><code>GET cis3760f23-05.socs.uoguelph.ca/api/courses/CIS*3760</code></span>
            </li>
          </ul>
          <strong>Output:</strong>
          <ul>
            <pre id="getByIdDisplay" class="json"></pre>
            </span>
          </ul>
        </div>
      </div>

      <div class="endpointGet">
        <div class="titleDiv" onclick="toggleDropdown(this)">
          <div class="methodGet">GET</div>
          <div class="subTitle">
            <p><strong>cis3760f23-05.socs.uoguelph.ca/api/courses/prerequisites</strong></p>
          </div>
        </div>
        <div class="parameter">
          <p>This API gets the list of prerequisites from the server for each individual course within a department,
            where the department is specified in the dept query parameter.</p>
          <strong>Parameters:</strong>
          <ul>
            <li>
              <span class="params"><span class="paramTitle">dept</span> - The department you want to search
                within</span>
            </li>
          </ul>
          <strong>Example Usage:</strong>
          <ul>
            <li>
              <span
                class="params"><code>GET cis3760f23-05.socs.uoguelph.ca/api/courses/prerequisites?dept=ACCT</code></span>
            </li>
            <li>
              <span
                class="params"><code>GET cis3760f23-05.socs.uoguelph.ca/api/courses/prerequisites/MGMT*4000</code></span>
            </li>
          </ul>
          <strong>Output:</strong>
          <ul>
            <li>
              <span class="params">List of all prerequisites by department.
              </span>
            </li>
            <pre id="getPrerequisitesByDepartment" class="json"></pre>
            <li>
              <span class="params">List of all prerequisites by course.
              </span>
            </li>
            <pre id="getPrerequisitesByCourse" class="json"></pre>
          </ul>
        </div>
      </div>


      <div class="endpointDelete">
        <div class="titleDiv" onclick="toggleDropdown(this)">
          <div class="methodDelete">DELETE</div>
          <div class="subTitle">
            <p><strong>cis3760f23-05.socs.uoguelph.ca/api/courses/{{id}}</strong></p>
          </div>
        </div>
        <div class="parameter">
          <p>Given a course ID, deletes the course and it's parameters, returns message.</p>
          <strong>Parameters:</strong>
          <ul>
            <li>
              <span class="params"><span class="paramTitle">id</span> - Path parameter specifying the course code of the
                course you wish to use</span>
            </li>
          </ul>
          <strong>Example Usage:</strong>
          <ul>
            <li>
              <span class="params"><code>DELETE cis3760f23-05.socs.uoguelph.ca/api/courses/CIS*2030</code></span>
            </li>
          </ul>
          <strong>Output:</strong>
          <ul>
            <textarea readonly>
      {
        "message": "Course 'CIS*2030' has been deleted"
      }</textarea>
            </span>
          </ul>
        </div>
      </div>

      <div class="endpointPut">
        <div class="titleDiv" onclick="toggleDropdown(this)">
          <div class="methodPut">PUT</div>
          <div class="subTitle">
            <p><strong>cis3760f23-05.socs.uoguelph.ca/api/courses/{{id}}</strong></p>
          </div>
        </div>
        <div class="parameter">
          <p>Given a course ID, and a course parameter, updates the parameter of that course, returns message.</p>
          <strong>Parameters:</strong>
          <ul>
            <li>
              <span class="params"><span class="paramTitle">id</span> - Path parameter specifying the course code of the
                course you wish to use</span>
            </li>
            <li>
              <span class="params"><span class="paramTitle">course_name (Optional)</span> - The name of the
                course</span>
            </li>
            <li>
              <span class="params"><span class="paramTitle">course_description (Optional)</span> - The description of
                the
                course</span>
            </li>
            <li>
              <span class="params"><span class="paramTitle">timings (Optional)</span> - The timings of the course</span>
            </li>
            <li>
              <span class="params"><span class="paramTitle">lecture (Optional)</span> - The lecture number of the
                course</span>
            </li>
            <li>
              <span class="params"><span class="paramTitle">credits (Optional)</span> - The credit weight of the
                course</span>
            </li>
          </ul>
          <strong>Example Usage:</strong>
          <ul>
            <li>
              <span class="params"><code>PUT cis3760f23-05.socs.uoguelph.ca/api/courses/MGMT*3300</code></span>
            </li>
            <textarea readonly>
      {
        "course_name": "Project Management"
      }</textarea>

          </ul>
          <strong>Output:</strong>
          <ul>
            <textarea readonly>
      {
        "message": "Course 'MGMT*3300' has been updated"
      }</textarea></span>

          </ul>
        </div>
      </div>
      <script type="text/javascript" src="/sub_sites/Documentation/js/apiCalls.js"></script>
      <script type="text/javascript" src="/sub_sites/Documentation/js/index.js"></script>
      <script>
        function toggleDropdown(titleDiv) {
          const parameters = titleDiv.parentElement.querySelector('.parameter');
          parameters.classList.toggle('open');
        }
        function onload() {
          var textareas = document.getElementsByTagName('textarea');

          for (var i = 0; i < textareas.length; i++) {
            textareas[i].style.height = "5px";
            textareas[i].style.height = (textareas[i].scrollHeight) - 20 + "px";
          }
        }
      </script>
    </body>

</html>