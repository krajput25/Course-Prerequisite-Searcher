# User Stories
---
## Student who wishes to view a list of courses that they are eligible to take in the future, based on a list of completed courses. 
An **undergraduate** student at the University of Guelph wants to plan their academic degree and wishes to see a list of future courses
they are eligible to take, by providing a list of courses they have already completed and earned credits for, or are currently taking. 

### Scenario

1. **User Role**: 1st year Computing Undergraduate student
2. **Goal**: View all available courses they can take for second year, given the courses they have completed.

### Actions

The **student** wishes to view all the courses available to them given their completed and in-progress courses.  
They perform the following step:
  
#### **Inputing courses**: 
The **User** inputs their courses separated by commas into the search bar.

* Input: ```CIS*1300, CIS*1910, CIS*1250, CIS*2250, CIS*2500, MATH*1160```

#### **Result**: 
The system displays all courses, and their respective attributes, that the student can take in their future semesters based on the courses that they inputed and hence have or will have gained credits for. 

### Outcome

The **student** is successfully able to see all the courses they can take in the future based on the prerequiistes they have fulfilled. Once the user has inputed the courses and hit search, the system will display individual course information in the form of collapsible cards, on the UI.  

---
## Student who wishes to view a list of courses that they are eligible to take in the future, based on a list of completed courses. 
An **undergraduate** student at the University of Guelph wants to plan their academic degree and wishes to see a list of future courses
they are eligible to take, by providing a list of courses they have already completed and earned credits for, or are currently taking. 

### Scenario

1. **User Role**: 1st year Biological Science Undergraduate student
2. **Goal**: View all available courses they can take for second semester, given the courses they have completed (required and electives) in the first semester.

### Actions

The **student** wishes to view all the courses available to them given their completed courses (required and electives).  
They perform the following step:
  
#### **Inputing courses**: 
The **User** inputs their courses separated by commas into the search bar.

* Input: ```BIOL*1090, CHEM*1040, MATH*1080, PHYS*1080, CIS*2050```

#### **Result**: 
The system displays all courses, and their respective attributes, that the student can take in their future semesters based on the courses that they inputed and hence have or will have gained credits for. 

### Outcome

The **student** is successfully able to see all the courses they can take in the future based on the prerequiistes they have fulfilled. Once the user has inputed the courses and hot search, the system will display individual course information in the form of collapsible cards, on the UI.  

---

## Student wishes to see a list of courses that a particular course they have taken is a prerequisite for. 
An **undergraduate** student at the University of Guelph wants view all courses that require the completion of a particular course that they have completed or are taking. 

### Scenario

1. **User Role**: Undergraduate student
2. **Goal**: View a list of courses that need the completion of a particular course the student has completed. 

### Actions

The **student** wishes to view all the courses available to them given that they have completed a particular course.  
They perform the following step:
  
#### **Inputing courses**: 
The **User** inputs the single course in the search bar.

* Input: ```CIS*2750```

#### **Result**: 
The system displays all courses, and their respective attributes, that the student can take based on their completion of CIS*2750. These are the courses that have one of their prerequisites as CIS*2750.

### Outcome

The **student** is successfully able to see all the courses they can take in the future based on their completion of the inputed course, and review eligibility. These are in the form of collapsible cards on the UI. 

---

