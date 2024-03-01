from classes.CourseData import CourseData
from classes.CsvCreator import create_csv
from parse_prerequisites import parse_into_rows
from playwright.sync_api import Page, sync_playwright;

def scrape_calendar_for_links(page: Page):
    page.goto("https://calendar.uoguelph.ca/undergraduate-calendar/course-descriptions/")
    embedded_links = []
    for link in page.locator("a").all():
        href = link.get_attribute("href")
        if (href is None or 
            href in embedded_links or
            "/undergraduate-calendar/course-descriptions/" not in href or 
            href == "/undergraduate-calendar/course-descriptions/"
            or "." in href):
            continue
        embedded_links.append(href)
            
    new_links = []
    for link in embedded_links:
        new_links.append(f"https://calendar.uoguelph.ca{link}")
        
    for link in new_links:
        print(link)
        
    return new_links
    
def get_page(playwright):
    chromium = playwright.chromium
    browser = chromium.launch()
    page = browser.new_page()
    return page

def scrape_page(page: Page, link: str): 
    page.goto(link)
    new_courses = []
    for div in page.locator(".courseblock").all():
        course_details = div.text_content().replace("  ", " ").replace("\n", " ").replace(u"\xa0", " ")
        course_desc = course_details[course_details.find("]") + 1:]
        split_locs = [course_desc.find("Offering(s)"),
                course_desc.find("Restriction(s)"),
                course_desc.find("Location(s)"),
                course_desc.find("Department(s)"),
                course_desc.find("Prerequisite(s)"),
                course_desc.find("Co-requisite(s)"),
                course_desc.find("Equate(s)")]
    
        split_locs = [i for i in split_locs if i != -1]
        split_locs.append(len(course_desc))
        split_locs.sort()

        offerings = corequisites = equates = restrictions = departments = locations = prerequisites = ""
        
        for j in range(0, len(split_locs) - 1):
            current_split = course_desc[split_locs[j]:split_locs[j + 1]].strip()
            if "Offering(s)" in current_split:
                offerings = current_split.removeprefix("Offering(s): ").replace("\n", "")
            elif "Restriction(s)" in current_split:
                restrictions = current_split.removeprefix("Restriction(s): ").replace("\n", "")
            elif "Department(s)" in current_split:
                departments = current_split.removeprefix("Department(s): ").replace("\n", "")
            elif "Location(s)" in current_split:
                locations = current_split.removeprefix("Location(s): ").replace("\n", "")
            elif "Prerequisite(s)" in current_split:
                prerequisites = current_split.removeprefix("Prerequisite(s): ").replace("\n", "")
            elif "Equate(s)" in current_split:
                equates = current_split.removeprefix("Equate(s): ").replace("\n", "")
            elif "Co-requisite(s)" in current_split:
                corequisites = current_split.removeprefix("Co-requisite(s): ").replace("\n", "")

        course_code = course_details[0:course_details.find(" ")]

        dates_locations = [course_details.find("Winter"), course_details.find("Fall"), course_details.find("Summer")]
        while True:
            try: 
                dates_locations.remove(-1)
            except:
                break; 

        dates_locations.sort()

        if "Unspecified  [" in course_details:
            name = course_details[course_details.find(" "):dates_locations[0] if len(dates_locations) > 0 else course_details.find("Unspecified")]
        elif "DVM Phase" in course_details:
            name = course_details[course_details.find(" "):dates_locations[0] if len(dates_locations) > 0 else course_details.find("DVM Phase")]
        else: 
            name = course_details[course_details.find(" "):dates_locations[0] if len(dates_locations) > 0 else course_details.find("(")]
        if (course_details.find("(") == -1 or course_details.find("[") < course_details.find("(")):
            terms = course_details[dates_locations[0]:course_details.find("[")] if len(dates_locations) > 0 else ""
        else:
            terms = course_details[dates_locations[0]:course_details.find("(")] if len(dates_locations) > 0 else ""
        lecture_time = course_details[course_details.find("(")+1:course_details.find(")")]
        credits = course_details[course_details.find("[")+1:course_details.find("]")]
        

        new_courses.append(
            CourseData(course_code.strip(),
                name.strip(),
                terms.strip(), 
                offerings.strip(), 
                lecture_time.strip() if "LAB" in lecture_time or "LEC" in lecture_time else "",
                credits,
                course_desc[0:split_locs[0]].strip(),
                prerequisites,
                corequisites,
                restrictions,
                departments,
                locations,
                equates))
        
    return new_courses
    
with sync_playwright() as playwright:
    page = get_page(playwright)
    new_links = scrape_calendar_for_links(page)
    courses = []
    for link in new_links:
        courses += scrape_page(page, link)
    print(len(courses))
    create_csv(courses)
    parse_into_rows(courses)
    page.close()