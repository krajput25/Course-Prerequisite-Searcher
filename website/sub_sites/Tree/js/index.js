const getPrerequisiteTreeEndpoint = "https://cis3760f23-05.socs.uoguelph.ca/api/courses/prerequisites?dept=";
const getCoursePrerequisiteTreeEndpoint = "https://cis3760f23-05.socs.uoguelph.ca/api/courses/prerequisites/"
const getAllCourseEndpoint = "https://cis3760f23-05.socs.uoguelph.ca/api/courses";
const getAllDeptCodesEndpoint = "https://cis3760f23-05.socs.uoguelph.ca/api/courses/?departmentCodes";
const getSingleCourseEndpoint = "https://cis3760f23-05.socs.uoguelph.ca/api/courses/";

const courseInputField = document.getElementById("course");
const departmentInputField = document.getElementById('department');
const resultsBox = document.querySelector(".result_box");
const resultsBox2 = document.querySelector(".result_box2");

function getPrerequisiteTreeFromServer(url) {
    var XMLRequest = new XMLHttpRequest();
    var endpoint = url;
    XMLRequest.open('GET', endpoint, false);
    XMLRequest.send();
    return JSON.parse(XMLRequest.response);
}

function getAllCoursesFromServer() {
    var XMLRequest = new XMLHttpRequest();
    XMLRequest.open('GET', getAllCourseEndpoint, false);
    XMLRequest.send();
    return JSON.parse(XMLRequest.response);
  }

  function getAllDeptCodesFromServer() {
    var XMLRequest = new XMLHttpRequest();
    XMLRequest.open('GET', getAllDeptCodesEndpoint, false);
    XMLRequest.send();
    return JSON.parse(XMLRequest.response);
  }

function getSingleCourseInfoFromServer(course_code) {
  var XMLRequest = new XMLHttpRequest();
  XMLRequest.open('GET', getSingleCourseEndpoint + course_code, false);
  XMLRequest.send();
  return JSON.parse(XMLRequest.response);
}

function getAllCoursesInformation() {
    let result = [];
    let response = getAllCoursesFromServer();
  
    for(let single of response){
      result.push(single.course_code)
    }
    return result;
}

function getAllDepartmentCodes() {
    let result = [];
    let response = getAllDeptCodesFromServer();
  
    for(let single of response){
      result.push(single.department_code)
    }
    return result;
}

function capitalizeInput() {
  let input = document.getElementById("course");
  let words = input.value.split(" ");
  for (let i = 0; i < words.length; i++) {
    let letters = words[i].split("");
    for (let j = 0; j < letters.length; j++) {
      letters[j] = letters[j].toUpperCase();
    }
    words[i] = letters.join("");
  }
  input.value = words.join(" ");
}

function display(result, resultsBox, box) {
    if (result.length !== 0) {
      let content;
      if (box === 1) {
        content = result.map((list) => {
          return "<li onclick='selectLeftDropdown(this)'>" + list + "</li>";
        });
      } else {
        content = result.map((list) => {
          return "<li onclick='selectRightDropdown(this)'>" + list + "</li>";
        });
      }
      resultsBox.innerHTML = "<ul>" + content.join('') + "</ul>";
    } else {
      resultsBox.innerHTML = '';
    }
}

let courses = getAllCoursesInformation();
let departmentCodes = getAllDepartmentCodes();

function handleKeyUp(inputField, resultsBox, box) {
    return function () {
      let result = [];
      let input = inputField.value;
  
      // Capitalize every letter in the input
      inputField.value = input.toUpperCase();
      input = input.toUpperCase();
  
      if (input.length >= 1) {
          if(box == 1){
              result = departmentCodes.filter((keywords) => {
                  return keywords.includes(input);
              });
          }
          else{
              result = courses.filter((keywords) => {
                  return keywords.includes(input);
              });
          }
      }
      display(result, resultsBox, box);
    };
  }

departmentInputField.onkeyup = handleKeyUp(departmentInputField, resultsBox, 1);
courseInputField.onkeyup = handleKeyUp(courseInputField, resultsBox2, 2);

function selectLeftDropdown(list) {
    let lastSpaceIndex = departmentInputField.value.lastIndexOf(' ');

    let lastSeparatorIndex = Math.max(lastSpaceIndex);
  
    if (lastSeparatorIndex !== -1) {
      departmentInputField.value = departmentInputField.value.substring(0, lastSeparatorIndex + 1) + list.innerHTML;
    } else {
      departmentInputField.value = list.innerHTML;
    }
    resultsBox.innerHTML = '';
}

function selectRightDropdown(list) {
  let lastSpaceIndex = courseInputField.value.lastIndexOf(' ');

  let lastSeparatorIndex = Math.max(lastSpaceIndex);

  if (lastSeparatorIndex !== -1) {
    courseInputField.value = courseInputField.value.substring(0, lastSeparatorIndex + 1) + list.innerHTML;
  } else {
    courseInputField.value = list.innerHTML;
  }
  resultsBox2.innerHTML = '';
}

function createCourseGraph() {
    const enteredCourse = document.getElementById("course").value.trim();
    if (!enteredCourse) {
        // Provide feedback or handle the case when no department is entered
        alert("Please provide a course code before creating the graph.");
    }
    let responseBody = getPrerequisiteTreeFromServer(getCoursePrerequisiteTreeEndpoint + enteredCourse);
    createGraph(responseBody);
}

function createDepartmentGraph() {
    const enteredDepartment = document.getElementById("department").value.trim();
    if (!enteredDepartment) {
        // Provide feedback or handle the case when no department is entered
        alert("Please enter a department code before creating the graph.");
    }
    let responseBody = getPrerequisiteTreeFromServer(getPrerequisiteTreeEndpoint + enteredDepartment);
    createGraph(responseBody);
}

function createGraph(responseBody) {

    // Extract unique courses, edges, and nodes
    let uniqueCourses = [...new Set(responseBody.course_codes)];
    let edges = responseBody.edges.map(edge => ({ from: edge.hasPrerequisite, to: edge.course }));
    let nodes = uniqueCourses.map(course => ({ id: course, label: course }));

    // Create graph data
    let container = document.getElementById("prerequisitesContainer");
    let data = {
        nodes: new vis.DataSet(nodes),
        edges: new vis.DataSet(edges)
    };

    // Set graph options
    let options = {
        height: '600px',
        edges: {
            arrows: {
                to: {
                    enabled: true,
                    scaleFactor: 0.5
                }
            },
            color: {
                highlight: 'red', 
                hover: 'black' // Set the color when arrows are hovered
            },
        },
        nodes: {
            shape: 'box',
            size: 10,
            color: {
                highlight: {
                    border: 'black'
                }
            }
        },
        layout: {
            improvedLayout: true,
            clusterThreshold: 250,
            hierarchical: {
                enabled: true,
                treeSpacing: 150,
                levelSeparation: 350,
                direction: "LR",
                edgeMinimization: false,
                parentCentralization: false,
                sortMethod: "directed",
                shakeTowards: 'roots'
            }
        },
        interaction: {
            hoverConnectedEdges: true,
            hover: true, 
            navigationButtons: true,
            keyboard: {
                enabled: true,
                speed: { x: 10, y: 10, zoom: 0.02 },
                bindToWindow: true
            },
            tooltipDelay: 300, 
            zoomView: true, 
            dragView: true,
        },
    };
    // Create the graph
    let network = new vis.Network(container, data, options);

    network.on('click', function (event) {
      const { nodes } = event;
      if (nodes.length > 0) {
        const selectedNodeId = nodes[0];
        const selectedNode = network.body.nodes[selectedNodeId];

        // Get the label of the selected node
        const nodeLabel = selectedNode.options.label;
        const courseInfo = getSingleCourseInfoFromServer(nodeLabel);
        console.log(courseInfo);
        // Display an overlay box with the text
        showOverlay(courseInfo);
      }
    });
}

function showOverlay(courseInfo) {
  // Create a new div element for the overlay
  const overlayDiv = document.createElement("div");
  overlayDiv.id = "customOverlay";

  const parentDiv = document.createElement("div");
  parentDiv.id = "parentDiv"; 
  // Create a paragraph element to display the text
  const heading = document.createElement("h2");
  heading.textContent = "Course Information";
  const subHeading = document.createElement("h3");
  subHeading.textContent = courseInfo.course_code + ": " + courseInfo.course_name + " (" + courseInfo.credits + ")";

  const description = document.createElement("p");
  description.textContent = courseInfo.course_description;

  const prerequisites = document.createElement("p");
  if(courseInfo.prerequisites === "") {
    prerequisites.textContent = "Prerequisites: None";
  }else{
    prerequisites.textContent = "Prerequisites: " + courseInfo.prerequisites;
  }

  const terms = document.createElement("p");
  terms.textContent = "Offerings: " + courseInfo.terms;

  let lecture_flag = 0;
  const lecture = document.createElement("p");
  if (courseInfo.lecture != "") {
    lecture_flag = 1;
    lecture.textContent = "Lecture Timings: " + courseInfo.lecture;
  }

  const location = document.createElement("p");
  location.textContent = "Location: " + courseInfo.course_location; 

  // Create a close button
  const closeButton = document.createElement("button");
  closeButton.id = "overlayButton"
  closeButton.textContent = "Close";
  closeButton.style.marginTop = "10px";
  closeButton.addEventListener("click", function () {
    overlayDiv.style.display = "none";
    overlayDiv.remove();
  });

  // Append elements to the overlay div
  overlayDiv.appendChild(parentDiv);
  parentDiv.appendChild(heading);
  parentDiv.appendChild(subHeading);
  parentDiv.appendChild(description);
  parentDiv.appendChild(prerequisites);
  parentDiv.appendChild(terms);
  if(lecture_flag === 1) {
    parentDiv.appendChild(lecture);
  }
  parentDiv.appendChild(location);
  parentDiv.appendChild(closeButton);

  // Append the overlay div to the body
  document.body.appendChild(overlayDiv);
}
