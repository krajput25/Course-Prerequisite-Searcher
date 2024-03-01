import csv
from classes.CourseData import CourseData

def create_csv(courses: CourseData):
    # Define the headers for the csv file
    fields = [
        "Course Code", "Name", "Terms", "Timings", "Lecture", 
        "Credits", "Description", "Prerequisites", "Co-requisites",
        "Restrictions", "Department", "Location"
    ]

    # Specify filename
    filename = "parsed_courses.csv"

    # Writing to csv
    with open(filename, 'w', newline='', encoding='utf-8') as csvfile:
        # Create csv dict writer obj
        csvwriter = csv.DictWriter(csvfile, fieldnames=fields, delimiter="€")
        
        # Write header
        csvwriter.writeheader()
        
        # Write data
        for course in courses:
            csvwriter.writerow({
                "Course Code": course.code,
                "Name": course.name,
                "Terms": course.terms,
                "Timings": course.timings,
                "Lecture": course.lecture,
                "Credits": course.credits,
                "Description": course.description,
                "Prerequisites": course.prerequisites,
                "Co-requisites": course.corequisites,
                "Restrictions": course.restrictions,
                "Department": course.department,
                "Location": course.location
            })
        csvfile.close()
