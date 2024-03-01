from csv import DictWriter
import csv
import re

fields = [
    "Course Code", "Prerequisites", "Group", "Subgroup", "Condition Code",
    "Multiple in Group", "Credit Amount", "Credit Type", "Course Level", 
    "Phase", "Music Core", "High School", "Extras"
]
filename = "prerequisites_table.csv"

# Note the calculus requirement, this is a restriction
department_dict = {
    "MATH": "MATH",
    "STAT": "STAT",
    "Statistics": "STAT",
    "Mathematics": "MATH",
    "mathematics": "MATH",
    "Art History": "ARTH",
    "ARTH": "ARTH",
    "Ecology": "ECOL",
    "Chemisty": "CHEM",
    "Classics": "CLAS",
    "Classical Studies": "CLAS",
    "CIS": "CIS",
    "CTS": "CTS",
    "Calculus": "MATH",
    "English": "ENGL",
    "Theatre Studies": "THST",
    "European Studies": "EURO",
    "science": "IDFK",
    "engineering": "ENGG",
    "French": "FREN",
    "FARE": "FARE",
    "ECON": "ECON",
    "Geography": "GEOG",
    "German": "GERM",
    "GERM": "GERM",
    "History": "HIST",
    "HIST": "HIST",
    "ASCI": "ASCI",
    "botany": "PLSC",
    "PHYS": "PHYS",
    "physics": "PHYS",
    "IDEV": "IDEV",
    "Italian": "ITAL",
    "ITAL": "ITAL",
    "BIOC": "BIOC",
    "MBG": "MBG",
    "MCB": "MCB",
    "MICR": "MICR",
    "music": "MUSC", 
    "Music": "MUSC",
    "NANO": "NANO",
    "biology": "BIOL",
    "Philosophy": "PHIL",
    "Political Science": "POLS",
    "biochemistry": "BIOC",
    "chemistry": "CHEM",
    "SOC": "SOC",
    "POLS": "POLS",
    "Public Policy": "CJPP",
    "Governance and Law": "JLS", 
    "Psychology": "PHYC",
    "Philosophy": "PHIL",
    "Spanish": "SPAN",
    "Studio Art": "SART",
    "Theatre Studies": "THST",
    "Food Science": "FOOD"
}


def parse_into_rows(courses): 
    
    with open(filename, 'w', newline='', encoding='utf-8') as csvfile:
        csvwriter = csv.DictWriter(csvfile, fieldnames=fields, delimiter="€")
        csvwriter.writeheader()
        unwritten = []
        for course in courses:
            return_val = parse_course_into_rows(course, course.prerequisites, csvwriter, 1, 1, False, False, False)
            if return_val is not None:
                _, _, written = return_val
                if not written:
                    unwritten.append(course)
        
        for course in unwritten:
            parse_unwritten_course(course, csvwriter)

        csvfile.close()
        
def parse_course_into_rows(course, prerequisites:str, csvwriter: DictWriter, 
                           group: int, subgroup: int, is_embedded_multiple: bool, 
                           written: bool, music_core: bool):

    prerequisites = prerequisites.replace(") (", "), (")
    
    # Whether or not it matches "1 of ..."
    is_multiple = re.search("((([0-9])|(One)|(one)) of (?![^(\[]*(\)|\])))",
                            prerequisites) is not None
    
    prereq_string:str = prerequisites
    
    bad_syntax = re.search("[A-Za-z]{2,4}\*[0-9]{4} (([A-Za-z]{2,4}\*[0-9]{4})|(\())", prereq_string)
    if bad_syntax is not None:
        prereq_string = prereq_string.replace(prereq_string[bad_syntax.start():bad_syntax.end()], 
            prereq_string[bad_syntax.start():bad_syntax.end()].replace(" ", ", "))

    split_prereqs = re.split("(,(?![^[\]]*]|[^()]*\)))|([Ii]ncluding)|([Pp]lus)", prereq_string)
        
    if prerequisites is None or prerequisites.strip() == "":
        written = True
        csvwriter.writerow({
            "Course Code": course.code,
            "Prerequisites": "",
            "Group": 1,
            "Subgroup": 1,
            "Music Core": 1 if music_core else 0
        })
        return
        
    num_matched = "" if not is_multiple else (
        "1" if "One" in split_prereqs[0] else split_prereqs[0][0])

    for prereq in split_prereqs:
        if prereq is None:
            continue
        prereq = prereq.strip()
        if len(prereq) == 0:
            continue
        prereq = prereq.strip(".")
        if "; " in prereq:
            prereq = prereq[0:prereq.find("; ")]
        prereq.strip(";")
        
        prereq = prereq.replace("0 Students must have", "0, students must have")
        prereq = prereq.replace(") 0.50", "), 0.50")
        prereq = prereq.replace("1.50 History credits", "1.50 credits HIST")
        prereq = prereq.replace("of which must be in Political Science.", "credits in POLS")
        
        if ((prereq.startswith("(") and prereq.endswith(")")) or
            (prereq.startswith("[") and prereq.endswith("]"))):
            existing_prereqs = prerequisites
            prerequisites = (prereq.lstrip("(").lstrip("[")
                .rstrip(")").rstrip("]"))
            (group, subgroup, written) = \
                parse_course_into_rows(course, prerequisites, csvwriter, 
                                       group, subgroup, is_multiple, written, music_core)
            course.prerequisites = existing_prereqs
            group = group + 1
            subgroup = 1
        
        prereq = re.sub(r"(?![^()]*\))((\b[0-9]|One|one) of )", "", prereq)                

        if re.match("^[A-Za-z]{2,4}\*[0-9]{4}$", prereq) is not None:
            written = True
            csvwriter.writerow({
                "Course Code": course.code,
                "Prerequisites": course.prerequisites,
                "Group": group,
                "Subgroup": subgroup,
                "Condition Code": prereq,
                "Multiple in Group": num_matched if num_matched.isdigit() else 0,
                "Music Core": 1 if music_core else 0
            })
            group = group + (1 if (is_multiple is False and
                                   is_embedded_multiple is False)  
                            else 0)
            subgroup = (subgroup + 1 if (is_multiple is True and
                                         is_embedded_multiple is False)
                        else subgroup)
            
        if re.match("[A-Za-z]{2,4}\*[0-9]{4} or .*", prereq) is not None:
            or_condition = prereq.split(" or ", 1)
            for code in or_condition:
                code = code.strip()
                if ((code.startswith("(") and code.endswith(")")) or
                    (code.startswith("[") and code.endswith("]"))):
                    existing_prereqs = prerequisites
                    prerequisites = (code.lstrip("(").lstrip("[")
                        .rstrip(")").rstrip("]"))
                    (group, subgroup, written) = \
                        parse_course_into_rows(course, prerequisites, 
                                        csvwriter, group, subgroup, True, written, music_core)
                    prerequisites = existing_prereqs
                else:
                    written = True
                    csvwriter.writerow({
                        "Course Code": course.code,
                        "Prerequisites": course.prerequisites,
                        "Group": group,
                        "Subgroup": subgroup,
                        "Condition Code": code,
                        "Multiple in Group": num_matched if num_matched.isdigit() else 0,
                        "Music Core": 1 if music_core else 0
                    })
                subgroup = subgroup + 1
            group = group + 1
            subgroup = 1

                
        if re.match("[A-Za-z]{2,4}\*[0-9]{4} and .*", prereq) is not None:
            or_condition = prereq.split(" and ")
            for code in or_condition:
                code = code.strip()
                written = True
                csvwriter.writerow({
                    "Course Code": course.code,
                    "Prerequisites": course.prerequisites,
                    "Group": group,
                    "Subgroup": subgroup,
                    "Condition Code": code,
                    "Music Core": 1 if music_core else 0
                })
            subgroup = subgroup + 1
        
        
        credit_match = re.findall("[0-9.]+ credit[s]*", prereq)
        if len(credit_match) != 0: 
            credit_match_or = re.findall("[0-9.]+ credit[s]* or", prereq)
            if len(credit_match_or) != 0:
                split_credit_prereq = prereq.split(" or ", 1)
                _, subgroup, written = parse_course_into_rows(course, split_credit_prereq[1], csvwriter, group, subgroup, False, written, music_core)
                prereq = split_credit_prereq[0]
            credits_amount = credit_match[0][0:credit_match[0].find(" ")]
            department_names = []
            
            minimum_course_level = re.findall("[1-6]{1}000[\- ]*level", prereq)
            
            course_level = ""
            if len(minimum_course_level) != 0:
                course_level = minimum_course_level[0][0:4]
            
            for key, value in department_dict.items():
                if key in prereq:
                    if key == "History" and "Art History" in prereq:
                        continue
                    department_names.append(value)
            written = True
            csvwriter.writerow({
                "Course Code": course.code,
                "Prerequisites": course.prerequisites,
                "Group": group,
                "Subgroup": subgroup,
                "Multiple in Group": num_matched if num_matched.isdigit() else 0,
                "Credit Amount": credits_amount,
                "Credit Type": ",".join(department_names),
                "Course Level": course_level,
                "Music Core": 1 if music_core else 0
            })
            group = group + 1 if not is_multiple else group
            subgroup = subgroup + 1 if is_multiple else 1

    return (group, subgroup, written)

def parse_unwritten_course(course, csvwriter: DictWriter):
    
    course_match = re.search("[A-Za-z]{2,4}\*[0-9]{4}", course.prerequisites)
    music_core = "Completion of the music core" in course.prerequisites
    course.prerequisites.replace("Completion of the music core.", "")
    course.prerequisites.replace("Completion of the music core, ", "")
    course.prerequisites.replace("or equivalent", "")
    course.prerequisites.replace(").", "")
        
    if "All Phase" in course.prerequisites:
        csvwriter.writerow({
            "Course Code": course.code,
            "Prerequisites": course.prerequisites,
            "Group": 1,
            "Subgroup": 1,
            "Phase": course.prerequisites.split(" ")[2]
        })
    # Special case: Ugly prereq brackets breaks things
    elif course.code == "ENVS*3290":
        course.prerequisites = "ENVS*2080 or ENVS*2320 or [MBG*2040, (BIOL*2060 or MICR*2420)]"
        parse_course_into_rows(course, course.prerequisites, csvwriter, 1, 1, False, False, music_core)
    elif "One of (" in course.prerequisites:
        course.prerequisites = course.prerequisites.replace("One of (", "").replace(")", "")
        parse_course_into_rows(course, course.prerequisites, csvwriter, 1, 1, False, False, music_core)
    elif "Take " in course.prerequisites:
        course.prerequisites = course.prerequisites.replace("Take ", "")
        parse_course_into_rows(course, course.prerequisites, csvwriter, 1, 1, False, False, music_core)
    elif "COOP*" in course.prerequisites:
        course.prerequisites = course.prerequisites[course.prerequisites.find("COOP*"):]
        parse_course_into_rows(course, course.prerequisites, csvwriter, 1, 1, False, False, music_core)
    elif course_match is not None and course_match.start() == 0:
        course.prerequisites = course.prerequisites[course_match.start():course_match.end()]
        parse_course_into_rows(course, course.prerequisites, csvwriter, 1, 1, False, False, music_core)
    elif f"A minimum grade of 70% in " in course.prerequisites:
        course.prerequisites = course.prerequisites.replace(f"A minimum grade of 70% in ", "")
        parse_course_into_rows(course, course.prerequisites, csvwriter, 1, 1, False, False, music_core)
    elif "4U" in course.prerequisites or "Grade 12" in course.prerequisites or "12U" in course.prerequisites:
        csvwriter.writerow({
            "Course Code": course.code,
            "Prerequisites": course.prerequisites,
            "Group": 1,
            "Subgroup": 1,
            "High School": 1
        })
    elif course.code == "AGR*2500":
        course.prerequisites = "AGR*1110 or ARG*1250"
        parse_course_into_rows(course, course.prerequisites, csvwriter, 1, 1, False, False, music_core)
    elif course.code == "MATH*4440":
        course.prerequisites = "3.0 credits MATH STAT 3000 level"
        parse_course_into_rows(course, course.prerequisites, csvwriter, 1, 1, False, False, music_core)
    elif course.code == "POLS*4970" or course.code == "HUMN*3300":
        csvwriter.writerow({
            "Course Code": course.code,
            "Prerequisites": course.prerequisites,
            "Condition Code": course.code,
            "Group": 1,
            "Subgroup": 1,
            "Extras": course.prerequisites
        })
    else:
        csvwriter.writerow({
            "Course Code": course.code,
            "Prerequisites": course.prerequisites,
            "Group": 1,
            "Subgroup": 1,
            "Music Core": 1 if music_core else 0
        })
