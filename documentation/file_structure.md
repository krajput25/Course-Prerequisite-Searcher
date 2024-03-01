# Defined File Structure
This documentation details the file structure that we should be following as a group in order to maintain a clean repository. 

## Rules for file naming and directory structure
All file names and folder names should be snake_case, unless reasons permit that it should not be. Please put all files in the correct directory, as described in the `directories` section of this document. 

The only things that shoudl go in the root directory are .gitignore, README, and any other files that should be applied to all directories. 

## Directories
There are 3 main subdirectories from the root of this folder. `documentation`, `prerequisite_parser`, and `website`. These are defined below

`documentation`: This is where all .md files or equivalent documentation-style things will be held. This includes this document, sprint-end slides, user stories, etc... 

`prerequisite_parser`: Largely, this should remain untouched at the point of sprint5, and could probably be deleted after this sprint. This contains 3 subdirectories: `classes`, `files`, and `scraper`. 
- `classes`: Contains helper classes used by `file_parser.py` and `parse_prerequisites.py`. 
- `files`: Contains f23_courses2.txt, which is parsed by `file_parser.py`.

`website`: Contains the website that's being hosted on the school server. 
- `api`: Contains the API endpoints. 
- `data`: Contains configs, images, and the vba parser that's downloaded on the main page. 
- `js`: Contains dedicated JavaScript files. 
- `style`: Contains CSS files. 
- `sub_sites`: All sites besides the main functionality. 