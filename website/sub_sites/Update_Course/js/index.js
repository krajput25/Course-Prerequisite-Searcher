const editCourseEndpoint = "https://cis3760f23-05.socs.uoguelph.ca/api/courses/index.php?id=";

function editCourseFromServer() {
    var course_code = document.getElementById("course_code").value;
    var password = document.getElementById("password").value;

    var payload = {};

    // Check each input field and include non-empty values in the payload
    var course_name = document.getElementById("course_name").value;
    if (course_name.trim() !== "") {
        payload.course_name = course_name;
    }

    var course_description = document.getElementById("course_description").value;
    if (course_description.trim() !== "") {
        payload.course_description = course_description;
    }

    var timings = document.getElementById("timings").value;
    if (timings.trim() !== "") {
        payload.timings = timings;
    }

    var lecture = document.getElementById("lecture").value;
    if (lecture.trim() !== "") {
        payload.lecture = lecture;
    }

    var credits = document.getElementById("credits").value;
    if (credits.trim() !== "") {
        payload.credits = credits;
    }

    // Check if there are any non-empty fields before making the API call
    if (Object.keys(payload).length === 0) {
        alert("No fields provided for update.");
        return;
    }

    var jsonString = JSON.stringify(payload);

    var XMLRequest = new XMLHttpRequest();
    var endpoint = editCourseEndpoint + course_code;
    XMLRequest.open('PUT', endpoint, false);
    XMLRequest.setRequestHeader('Content-type', 'application/json');
    XMLRequest.setRequestHeader('Authorization', password);
    XMLRequest.send(jsonString);

    if (XMLRequest.status === 200) {
        alert("Course successfully updated!");
    } else if (XMLRequest.status === 401) {
        alert("Incorrect password. Please check your authentication key and try again.");
    } else {
        alert("Failed to update course. Please check your input and try again.");
    }

    return JSON.parse(XMLRequest.response);
}

const button = document.getElementById("submitButton");