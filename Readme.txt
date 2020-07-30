1. File list:

index.php
booking_calendar.php
ajax_action.php
export_json.php
bms.css

2. Input file:

Code-Test-Input.json

3.Implementation:

a.I used plain PHP / Bootstrap 4 / Jquery to implement the function, it is different from Symfony MVC framework which I use now, I thought it might save time of setting up working environment;

b. All query library, font awesome, and css library from CDN;

c. I use input file instead of adding to database. I prefer using database, but not enough time to setup mysql working environment;

d. The basic function has been done, but have some bugs needs more time to fix; 

4.Issues:

a. Weekly calendar works with some bugs (I used most of my time on this);

b. Shopping cart delete function need to update total amount accordingly(Should use Jquery to partically load the page);

5.Sugguests:

a. Weekly calendar should use Bootstrap 'Full Calendar', which is not free software but will get better, robust, readable code;

b. Use room _id instead of room name to map will be a better solution;
 