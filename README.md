## Course Prerequisite Searcher 

Developed as part of the CIS 3760 course at the University of Guelph, this project began as an Excel application utilizing VBA. Its primary aim was to aid students in navigating and planning their future courses by analyzing their fulfilled prerequisites against the university's course offerings.

To enhance its functionality, the Prerequisite Parser was migrated to Python, enabling it to scrape pertinent course data from the UoG course calendar. This data was then parsed and stored within a MySQL database for efficient retrieval and management.

The project's user interface was revamped with a responsive and innovative design using PHP. Additionally, the incorporation of RESTful APIs facilitated seamless interaction with the database, empowering users to query prerequisite course information and streamline their degree planning process.

For further details on specific APIs implemented, please refer to the project documentation.


## To run the project locally 

Naviagte to `\website` and run the following command: 
```
php -S localhost:8080
```