
function displayAllCourses() {
  const courses = getAllCourse()
  const result = document.getElementById('courseInfoDisplay');
  const coursesJSON = JSON.stringify(courses, null, 2);
  result.textContent = coursesJSON;
}

function displayById() {
  const courses = getCourseById("CIS*3760");
  const result = document.getElementById('getByIdDisplay');
  const coursesJSON = JSON.stringify(courses, null, 2);
  result.textContent = coursesJSON;
}

function displayEligibleCourses() {
  const courses = getPrerequisites(["CIS*1300", "CIS*1910"], false, false, false);
  const result = document.getElementById('prerequisiteDisplay');
  const coursesJSON = JSON.stringify(courses, null, 2);
  result.textContent = coursesJSON;
}

function displayPrerequisitesByDepartment() {
  const courses = getPrerequisitesByDepartment("ACCT");
  const result = document.getElementById('getPrerequisitesByDepartment');
  const coursesJSON = JSON.stringify(courses, null, 2);
  result.textContent = coursesJSON;
}

function displayPrerequisitesByCourse() {
  const courses = getPrerequisitesByCourse("MGMT*4000");
  const result = document.getElementById('getPrerequisitesByCourse');
  const coursesJSON = JSON.stringify(courses, null, 2);
  result.textContent = coursesJSON;
}

document.addEventListener('DOMContentLoaded', function () {
  displayAllCourses();
  displayById();
  displayEligibleCourses();
  displayPrerequisitesByDepartment();
  displayPrerequisitesByCourse();
});