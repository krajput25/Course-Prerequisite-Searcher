const prerequisiteEndpoint =
  "https://cis3760f23-05.socs.uoguelph.ca/api/courses/prerequisites";
const getCourseByIdEndpoint = "https://cis3760f23-05.socs.uoguelph.ca/api/courses/";
const getAllCourseEndpoint = "https://cis3760f23-05.socs.uoguelph.ca/api/courses";
const getByDepartmentEndpoint = "https://cis3760f23-05.socs.uoguelph.ca/api/courses?department=";
const getAllDeptCodesEndpoint = "https://cis3760f23-05.socs.uoguelph.ca/api/courses/?departmentCodes";

// const submitButton = document.getElementById("submitButton");
// submitButton.addEventListener("click", function() {getPrerequisites(['CIS*1300', 'CIS*1910', 'STAT*2040', 'ANTH*1160'])});

function getCourseInformation(inputCourses, creditReqs, noPrereqs, onlyCodes) {
  console.log('Courses - ', inputCourses);
  let elegibleCourses = getPrerequisites(inputCourses, creditReqs, noPrereqs, onlyCodes);
  return elegibleCourses;
}

function getPrerequisites(courses, creditReqs, noPrereqs, onlyCodes) {
  var XMLRequest = new XMLHttpRequest();
  var body = JSON.stringify({
    courses: courses,
    coursesWithCreditRequirement: creditReqs,
    includeEmpty: noPrereqs,
    displayCodesOnly: onlyCodes
  });
  XMLRequest.open("POST", prerequisiteEndpoint, false);
  XMLRequest.setRequestHeader('Content-type', 'application/json');
  XMLRequest.setRequestHeader('Accept', '*/*');
  XMLRequest.send(body);
  return JSON.parse(XMLRequest.response);
}



function getAllCoursesInformation() {
  let result = [];
  let response = getAllCoursesFromServer();

  for (let single of response) {
    result.push(single.course_code)
  }
  return result;
}

function getCourseById(inputCourse) {
  return getCourseByIdFromServer(inputCourse);
}

function getAllCourse() {
  let result = []
  let response = getAllCoursesFromServer();
  for (let single of response) {
    result.push(single);
    // console.log(single)
  }
  return result;
}

function getByDepartment(department) {
  console.log(department)
  let result = []
  let response = getByDepartmentFromServer(department);
  for (let course of response) {
    // console.log(response)
    result.push(course);
  }
  return result
}

function getAllDepartmentCodes() {
  let result = [];
  let response = getAllDeptCodesFromServer();

  for(let single of response){
    result.push(single.department_code)
  }
  return result;
}

function getAllCoursesFromServer() {
  var XMLRequest = new XMLHttpRequest();
  XMLRequest.open('GET', getAllCourseEndpoint, false);
  XMLRequest.send();
  return JSON.parse(XMLRequest.response);
}

function getCourseByIdFromServer(id) {
  var XMLRequest = new XMLHttpRequest();
  XMLRequest.open('GET', getCourseByIdEndpoint + id, false);
  XMLRequest.send();
  return JSON.parse(XMLRequest.response);
}

function getByDepartmentFromServer(department) {
  var XMLRequest = new XMLHttpRequest();
  XMLRequest.open('GET', getByDepartmentEndpoint + department, false);
  XMLRequest.send();
  return JSON.parse(XMLRequest.response);
}

function getPrerequisitesByDepartment(department) {
  var XMLRequest = new XMLHttpRequest();
  XMLRequest.open('GET', `${prerequisiteEndpoint}?dept=${department}`, false);
  XMLRequest.send();
  return JSON.parse(XMLRequest.response);
}

function getPrerequisitesByCourse(course) {
  var XMLRequest = new XMLHttpRequest();
  XMLRequest.open('GET', `${prerequisiteEndpoint}/${course}`, false);
  XMLRequest.send();
  return JSON.parse(XMLRequest.response);
}

function getAllDeptCodesFromServer() {
  var XMLRequest = new XMLHttpRequest();
  XMLRequest.open('GET', getAllDeptCodesEndpoint, false);
  XMLRequest.send();
  return JSON.parse(XMLRequest.response);
}
