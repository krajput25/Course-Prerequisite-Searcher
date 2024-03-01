
CREATE DATABASE IF NOT EXISTS course_db;
USE course_db;

DROP TABLE IF EXISTS prerequisites;
DROP TABLE IF EXISTS courses;

CREATE TABLE prerequisites (
    prerequisite_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(50),
    prerequisites TEXT,
    course_group INT,
    course_subgroup INT,
    condition_code VARCHAR(100),
    multiple_in_group INT,
    credit_amount FLOAT,
    credit_type VARCHAR(100),
    course_level INT,
    phase INT,
    music_core INT,
    highschool INT,
    extras VARCHAR(255)
);
CREATE TABLE courses (
    course_code VARCHAR(50) PRIMARY KEY,
    course_name VARCHAR(1000),
    terms VARCHAR(50),
    timings VARCHAR(255),
    lecture VARCHAR(255),
    credits VARCHAR(255),
    course_description TEXT,
    prerequisites TEXT,
    co_requisites TEXT,
    restrictions TEXT,
    department VARCHAR(1000),
    course_location VARCHAR(255)
);

LOAD DATA LOCAL INFILE './website/data/database_scripts/prerequisites_table.csv'
INTO TABLE prerequisites 
FIELDS TERMINATED BY '€'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(course_code, prerequisites, course_group, course_subgroup, condition_code, @vmultiple_in_group, @vcredit_amount, credit_type, @vcourse_level, @vphase, @vmusic_core, @vhighschool, extras)
SET multiple_in_group = IF(@vmultiple_in_group='',0,@vmultiple_in_group),
    course_level = IF(@vcourse_level='',0,@vcourse_level),
    phase = IF(@vphase='',0,@vphase),
    music_core = IF(@vmusic_core='',0,@vmusic_core),
    highschool = IF(@vhighschool='',0,@vhighschool),
    credit_amount = IF(@vcredit_amount='',0,@vcredit_amount);

LOAD DATA LOCAL INFILE './website/data/database_scripts/parsed_courses.csv'
INTO TABLE courses 
FIELDS TERMINATED BY '€'
OPTIONALLY ENCLOSED BY '\"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(course_code, course_name, terms, timings, lecture, credits, course_description, prerequisites, co_requisites, restrictions, department, course_location);
