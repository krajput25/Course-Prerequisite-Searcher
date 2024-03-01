const prerequisitesContainer = document.getElementById("prerequisitesContainer");
const inputField = document.getElementById("courseCode");
const button = document.getElementById("submitButton");
const searchButton = document.getElementById("searchButton");
const searchInputField = document.getElementById('searchCourseCode');
const resultsBox = document.querySelector(".result_box");
const resultsBox2 = document.querySelector(".result_box2");

function capitalizeInput() {
  let input = document.getElementById("courseCode");
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


let courses = getAllCoursesInformation();
let departmentCodes = getAllDepartmentCodes();

function display(result, resultsBox, box) {
  if (result.length !== 0) {
    let content;
    if (box === 1) {
      content = result.map((list) => {
        return "<li onclick='selectInput(this)'>" + list + "</li>";
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

function handleKeyUp(inputField, resultsBox, box) {
  let selectedIndex = -1; // Track selected index

  return function (event) {
    let result = [];
    let input = inputField.value;
    const searchDepartment = document.getElementById("searchByDepartmentCheckbox").checked;
;

    // Capitalize every letter in the input
    inputField.value = input.toUpperCase();
    input = input.toUpperCase();

    // Split input by both commas and spaces
    let parts = input.split(/[, ]+/);

    // Get the last part after the last separator
    let lastPart = parts[parts.length - 1];


    if (lastPart.length >= 1 && input.length >= 1) {
      if (searchDepartment) {
        result = departmentCodes.filter((keywords) => {
          return keywords.includes(input);
        });
      } else {
        result = courses.filter((keywords) => {
          return keywords.includes(lastPart);
        });
      }
    }

    display(result, resultsBox, box);

    // Handle arrow key navigation and enter key selection
    selectedIndex = handleKeyboardNavigation(event, resultsBox, selectedIndex);
    handleEnterKey(event, resultsBox, selectedIndex);
  };
}

function handleKeyboardNavigation(event, resultsBox, selectedIndex) {
  const listItems = resultsBox.querySelectorAll('ul li');
  const container = resultsBox.querySelector('ul');

  switch (event.key) {
    case 'ArrowDown':
      selectedIndex = (selectedIndex + 1) % listItems.length;
      break;
    case 'ArrowUp':
      selectedIndex = (selectedIndex - 1 + listItems.length) % listItems.length;
      break;
  }

  listItems.forEach((item, index) => {
    item.classList.toggle('selected', index === selectedIndex);
  });

  // Scroll the results box to keep the selected item in view
  const selectedElement = listItems[selectedIndex];
  if (selectedElement) {
    const scrollOffset = selectedElement.offsetTop - container.offsetTop;
    container.scrollTop = scrollOffset;
  }

  return selectedIndex;
}

function handleEnterKey(event, resultsBox, selectedIndex) {
  if (event.key === 'Enter' && selectedIndex !== -1) {
    const selectedElement = resultsBox.querySelector(`ul li:nth-child(${selectedIndex + 1})`);
    if (selectedElement) {
      // Check which input field active
      if (document.activeElement === inputField) {
        selectInput(selectedElement);
      } else if (document.activeElement === searchInputField) {
        selectRightDropdown(selectedElement);
      }
    }
  }
}


inputField.onkeyup = handleKeyUp(inputField, resultsBox, 1);
searchInputField.onkeyup = handleKeyUp(searchInputField, resultsBox2, 2);


function selectInput(list) {
  let lastCommaIndex = inputField.value.lastIndexOf(',');
  let lastSpaceIndex = inputField.value.lastIndexOf(' ');

  // Determine the last separator used
  let lastSeparatorIndex = Math.max(lastCommaIndex, lastSpaceIndex);

  if (lastSeparatorIndex !== -1) {
    inputField.value = inputField.value.substring(0, lastSeparatorIndex + 1) + list.innerHTML;
  } else {
    inputField.value = list.innerHTML;
  }

  resultsBox.innerHTML = '';

  // Reset selected state
  listItems.forEach((item) => {
    item.classList.remove('selected');
  });
}

function selectRightDropdown(list) {
  let lastSpaceIndex = searchInputField.value.lastIndexOf(' ');

  // Determine the last separator used
  let lastSeparatorIndex = Math.max(lastSpaceIndex);

  if (lastSeparatorIndex !== -1) {
    searchInputField.value = searchInputField.value.substring(0, lastSeparatorIndex + 1) + list.innerHTML;
  } else {
    searchInputField.value = list.innerHTML;
  }

  resultsBox2.innerHTML = '';
}


function handleButtonClick() {
  prerequisitesContainer.innerHTML = "";
  const inputFieldValue = getInputFieldValues();
  const creditReqs = document.getElementById("creditRequirementsCheckbox").checked;
  const noPrereqs = document.getElementById("noPrerequisitesCheckbox").checked;
  const onlyCodes = document.getElementById("codesOnlyCheckbox").checked;
  var wrongUserInput = false;
  if (inputFieldValue != "") {
    const courseCodes = processInputField(inputFieldValue);
    const courses = getCourseInformation(courseCodes, creditReqs, noPrereqs, onlyCodes);
    if (courses.length === 0) {
      displayErrorMessage(wrongUserInput);
    } else {
      createCourseCards(courses, onlyCodes);
    }
  } else if (inputFieldValue == "" && noPrereqs === true) {
    const courses = getCourseInformation([], creditReqs, noPrereqs, onlyCodes);
    if (courses.length === 0) {
      displayErrorMessage(wrongUserInput);
    } else {
      createCourseCards(courses, onlyCodes);
    }
  } else {
    wrongUserInput = true;
    displayErrorMessage(wrongUserInput);
  }
}

function handleSearchClick() {
  prerequisitesContainer.innerHTML = "";
  const inputFieldValue = searchInputField.value;
  const searchDepartment = document.getElementById("searchByDepartmentCheckbox").checked;
  let courseDetail;

  if (searchDepartment) {
    courseDetail = getByDepartment(inputFieldValue);

    if (courseDetail.length === 0) {
      displayErrorMessage(wrongUserInput);
    } else {
      createCourseCards(courseDetail, false);
    }
  }
  else {
    courseDetail = getCourseById(inputFieldValue);

    if (!courseDetail) {
      displayErrorMessage();
    } else {
      const card = createCourseCard(courseDetail);
      prerequisitesContainer.appendChild(card);
    }
  }
}

function getInputFieldValues() {
  const inputValue = inputField.value.trim();
  if (inputValue === "") {
    return inputValue;
  } else {
    return inputValue.split(",");
  }
}

function processInputField(inputValues) {
  const courseCodes = [];

  inputValues.forEach((input) => {
    // Split by commas and spaces and add each part to the courseCodes array
    const parts = input.split(/[, ]+/);
    parts.forEach((part) => {
      if (part.trim() !== "") {
        courseCodes.push(part.trim());
      }
    });
  });

  return courseCodes;
}

function displayErrorMessage(wrongUserInput) {
  const errorCard = document.createElement("div");
  errorCard.className = "error-card";

  var errorText = document.createElement("p");
  if (wrongUserInput) {
    errorText.textContent = "Either courses or the 'Show courses with no prerequisites' checkbox must be set.";
  } else {
    errorText.textContent = "No courses found matching the given parameters.";
  }
  errorCard.appendChild(errorText);

  prerequisitesContainer.appendChild(errorCard);
}

// Function to create course cards
function createCourseCards(courses, onlyCodes) {
  for (let courseDetail of courses) {
    if (!onlyCodes) {
      const card = createCourseCard(courseDetail);
      prerequisitesContainer.appendChild(card);
    } else {
      const card = createCourseCardCodeonly(courseDetail);
      prerequisitesContainer.appendChild(card);
    }
  }
}

function createCourseCardCodeonly(courseDetail) {
  const card = document.createElement("div");
  card.className = "course-card-codeonly";

  const titleContainer = document.createElement("div");

  const courseTitle = document.createElement("h3");
  courseTitle.textContent = `${courseDetail}`;
  titleContainer.appendChild(courseTitle);
  card.appendChild(titleContainer);

  return card;
}

function createCourseCard(courseDetail) {
  const card = document.createElement("div");
  card.className = "course-card";

  const titleContainer = createTitleContainer(courseDetail);
  card.appendChild(titleContainer);

  const contentContainer = createContentContainer(courseDetail);
  card.appendChild(contentContainer);

  card.classList.add("collapsed");
  apply3dHoverEffect(card);

  card.addEventListener("click", function () {
    toggleCardCollapse(card, contentContainer);
  });

  return card;
}

function createTitleContainer(courseDetail) {
  const titleContainer = document.createElement("div");
  titleContainer.className = "title-container";

  const courseTitle = document.createElement("h3");
  courseTitle.textContent = `${courseDetail.course_code}: ${courseDetail.course_name} (${courseDetail.credits})`;
  titleContainer.appendChild(courseTitle);

  titleContainer.style.cursor = "pointer";

  return titleContainer;
}

function createContentContainer(courseDetail) {
  const contentContainer = document.createElement("div");
  contentContainer.className = "content-container";

  const courseDescription = document.createElement("p");
  courseDescription.textContent = courseDetail.course_description;
  contentContainer.appendChild(courseDescription);


  const coursePrerequisites = document.createElement("p");
  coursePrerequisites.textContent = `Prerequisites: ${courseDetail.prerequisites}`;
  contentContainer.appendChild(coursePrerequisites);

  const semesterInfo = document.createElement("p");
  semesterInfo.textContent = `Offered: ${courseDetail.terms}`;
  contentContainer.appendChild(semesterInfo);

  const lectureDetails = document.createElement("p");
  if ((courseDetail.lecture).includes("LEC") || (courseDetail.lecture).includes("LAB")) {
    lectureDetails.textContent = `Lecture Details: ${courseDetail.lecture}`;
    contentContainer.appendChild(lectureDetails);
  }

  const locationInfo = document.createElement("p");
  locationInfo.textContent = `Location: ${courseDetail.course_location}`;
  contentContainer.appendChild(locationInfo);

  if (courseDetail.Corequisites) {
    const corequisitesInfo = document.createElement("p");
    corequisitesInfo.textContent = `Corequisites: ${courseDetail.co_requisites}`;
    contentContainer.appendChild(corequisitesInfo);
  }

  return contentContainer;
}

function toggleCardCollapse(card, contentContainer) {
  card.classList.toggle("collapsed");

  if (!card.classList.contains("collapsed")) {
    contentContainer.style.maxHeight = 'none';
    let contentHeight = contentContainer.scrollHeight;
    contentContainer.style.maxHeight = '0';
    setTimeout(() => contentContainer.style.maxHeight = contentHeight + 'px', 10);
  } else {
    contentContainer.style.maxHeight = '0';
  }
}


button.addEventListener("click", handleButtonClick);
searchButton.addEventListener("click", handleSearchClick);


// Zach's completely necessary aesthetics below

let isScrolling = false;

window.addEventListener('scroll', function () {
  isScrolling = true;
  clearTimeout(window.isScrollingTimeout);
  window.isScrollingTimeout = setTimeout(function () {
    isScrolling = false;
  }, 100); // Adjust timeout as needed
}, false);

function apply3dHoverEffect(card) {
  card.addEventListener('mousemove', (e) => {
    if (isScrolling) {
      return; // Do nothing if scrolling
    }

    const rect = card.getBoundingClientRect();
    // Check if card is in viewport
    if (rect.bottom < 0 || rect.top > window.innerHeight) {
      return; // Skip if card is not in viewport
    }

    const cardWidth = rect.width;
    const cardHeight = rect.height;
    const centerX = rect.left + cardWidth / 2;
    const centerY = rect.top + cardHeight / 2;
    const mouseX = e.clientX - centerX;
    const mouseY = e.clientY - centerY;
    const rotateX = ((1 - (mouseY / cardHeight)) * 15) * 0.5;
    const rotateY = ((mouseX / cardWidth) * 15) * 0.5;

    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
  });

  card.addEventListener('mouseout', () => {
    card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
  });
}

// Apply 3D effect to each card
const cards = document.querySelectorAll('.course-card');
cards.forEach((card) => {
  apply3dHoverEffect(card);
});
