class CourseData:
    def __init__(self, code, name, terms, timings, lecture, credits, description, prerequisites, corequisites, restrictions, department, location, equates):
        self.code = code
        self.name = name
        self.terms = terms
        self.timings = timings
        self.lecture = lecture
        self.credits = credits
        self.description = description
        self.prerequisites = prerequisites
        self.corequisites = corequisites
        self.restrictions = restrictions
        self.department = department
        self.location = location
        self.equates = equates

    def __str__(self):
        return f"Code: {self.code}\nName: {self.name}\nTimings: {self.timings}\nLecture: {self.lecture}\nCredits: {self.credits}\nDescription: {self.description}\nPrerequisites: {self.prerequisites}\nCo-requisites: {self.corequisites}\nRestrictions: {self.restrictions}\nDepartment: {self.department}\nLocation: {self.location}\nEquates: {self.equates}"