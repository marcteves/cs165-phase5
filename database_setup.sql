# Setup database and switch to it

CREATE DATABASE iskolivery;
USE iskolivery;

# Data Definition Language
# Define tables, views and create triggers

CREATE TABLE Status (
	id INT PRIMARY KEY,
	info VARCHAR(256) NOT NULL
);

CREATE TABLE ReportAction (
	id INT PRIMARY KEY,
	info VARCHAR(256) NOT NULL
);

CREATE TABLE Location (
	id INT AUTO_INCREMENT PRIMARY KEY,
	point POINT NOT NULL,
	short_name VARCHAR(256) NOT NULL
);

CREATE TABLE User (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(256) NOT NULL,
	upmail VARCHAR(256) NOT NULL UNIQUE,
	password VARCHAR(256) NOT NULL,
	rating FLOAT(3,2) NOT NULL DEFAULT 4.50,
	image_url VARCHAR(256), # appended to URI /web/www/images/
	location_id INT REFERENCES Location
);

CREATE TABLE Request (
	id INT AUTO_INCREMENT PRIMARY KEY, 
	posted_by INT NOT NULL REFERENCES User,
	info VARCHAR(256) NOT NULL,
	status_code INT(1) NOT NULL DEFAULT 0 REFERENCES Status,
	deadline DATE NOT NULL,
	location_id INT NOT NULL REFERENCES Location
);

CREATE TABLE Task (
	id INT AUTO_INCREMENT PRIMARY KEY, 
	last_assigned INT REFERENCES User,
	info VARCHAR(256) NOT NULL,
	status_code INT(1) NOT NULL DEFAULT 0 REFERENCES Status,
	bounty DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0.00,
	task_in INT NOT NULL REFERENCES Request ON DELETE CASCADE,
	location_id INT NOT NULL REFERENCES Location
);

CREATE TABLE Report (
	id INT AUTO_INCREMENT PRIMARY KEY, 
	info VARCHAR(256) NOT NULL,
	action_taken INT NOT NULL REFERENCES ReportAction,
	task_id INT NOT NULL REFERENCES Task,
	filed_by INT NOT NULL REFERENCES User,
	filed_against INT NOT NULL REFERENCES User
);

# Requester's rating on the fulfiller
CREATE TABLE FulfillerRating (
	rated_id INT NOT NULL REFERENCES User,
	task_id INT NOT NULL REFERENCES Task,
	rating FLOAT(3,2) NOT NULL
);

# Fulfiller's rating on the requester
CREATE TABLE RequesterRating (
	rated_id INT NOT NULL REFERENCES User,
	task_id INT NOT NULL REFERENCES Task,
	rating FLOAT(3,2) NOT NULL
);

# This view is used to store the index for fast queries on available closest
# tasks
CREATE VIEW NearbyTasks (id, last_assigned, poster, name, info, bounty,
	status_code, deadline, source, target, point) AS
SELECT Task.id, Task.last_assigned, Request.posted_by, User.name, Task.info,
	Task.bounty, Task.status_code, Request.deadline, A.short_name,
	B.short_name, A.point
FROM Request
JOIN Task ON Request.id = Task.task_in
JOIN Location AS A ON Task.location_id = A.id
JOIN Location AS B on Request.location_id = B.id
JOIN User ON Request.posted_by = User.id
WHERE Request.status_code > -1;

# Triggers to keep consistency

# ON HOLD DUE TO NEW LOCATION LAYOUT
# delimiter |
# 
# CREATE TRIGGER locationOnDeleteUser
# BEFORE DELETE ON User FOR EACH ROW
# BEGIN
# 	DELETE FROM Location WHERE Location.id = OLD.id;
# END;
# |
# delimiter ;
# 
# delimiter |
# 
# CREATE TRIGGER locationOnDeleteRequest
# BEFORE DELETE ON Request FOR EACH ROW
# BEGIN
# 	DELETE FROM Location WHERE Location.id = OLD.id;
# END;
# |
# delimiter ;
# 
# delimiter |
# 
# CREATE TRIGGER locationOnDeleteTask
# BEFORE DELETE ON Task FOR EACH ROW
# BEGIN
# 	DELETE FROM Location WHERE Location.id = OLD.id;
# END;
# |
# delimiter ;

# Data Manipulation Language
# Begin dummy values

INSERT INTO Status VALUES(-1, "Disabled (invisible to other Users).");
INSERT INTO Status VALUES(0, "Pending, not accepted");
INSERT INTO Status VALUES(1, "Pending, accepted");
INSERT INTO Status VALUES(2, "Complete");
INSERT INTO Status VALUES(3, "Aborted");

INSERT INTO ReportAction VALUES(0, "not processed");
INSERT INTO ReportAction VALUES(1, "under review");
INSERT INTO ReportAction VALUES(2, "ignored");
INSERT INTO ReportAction VALUES(3, "accused user banned");
INSERT INTO ReportAction VALUES(4, "accuser banned");

# Locations
INSERT INTO Location(point, short_name) VALUES(
	ST_GeomFromText('POINT(14.648539 121.068504)', 4326),
	'Department of Computer Science');
INSERT INTO Location(point, short_name) VALUES(
	ST_GeomFromText('POINT(14.656542 121.069663)', 4326),
	'Engineering Building');
INSERT INTO Location(point, short_name) VALUES(
	ST_GeomFromText('POINT(14.659593 121.069963)', 4326),
	'Shopping Center');
INSERT INTO Location(point, short_name) VALUES(
	ST_GeomFromText('POINT(14.654213 121.073371)', 4326),
	'Vinzon\'s Hall');
INSERT INTO Location(point, short_name) VALUES(
	ST_GeomFromText('POINT(14.659863 121.068461)', 4326),
	'Area 2');
INSERT INTO Location(point, short_name) VALUES(
	ST_GeomFromText('POINT(14.651421 121.074812)', 4326),
	'UP Town Center');
INSERT INTO Location(point, short_name) VALUES(
	ST_GeomFromText('POINT(14.652795 121.068543)', 4326),
	'AS FC Walk');
INSERT INTO Location(point, short_name) VALUES(
	ST_GeomFromText('POINT(14.653315 121.069738)', 4326),
	'AS Building');
INSERT INTO Location(point, short_name) VALUES(
	ST_GeomFromText('POINT(14.649147 121.069314)', 4326),
	'CS Lib');
INSERT INTO Location(point, short_name) VALUES(
	ST_GeomFromText('POINT(14.658852 121.068885)', 4326),
	'Kalayaan Residence Hall');
INSERT INTO Location(point, short_name) VALUES(
	ST_GeomFromText('POINT(14.655224 121.070982)', 4326),
	'Main Library');

# password : aaa
INSERT INTO User VALUES(1, "Maria", "maria@upd.edu.ph", "$2y$10$BAT8KYkO2jLi3bADyWsuWeptB28c8YempfwKe63m7NvyJFXZ6Jn6W", 4.50, NULL, 1);
# password : pass
INSERT INTO User VALUES(2, "Madeline", "madeline@upd.edu.ph", "$2y$10$5DDMA8GevEy0t.si5zmbheDvKNd3SF.zdoBTcBNTZiqdlEhtMOQNu", 4.50, NULL, 2);
# password : pass
INSERT INTO User VALUES(3, "Mary", "mary@upd.edu.ph", "$2y$10$WA5uTbyRpFaIQ6ffFRcBOeKtAp5PPc/czfpcLGTNyBsvbQN3gn.qC", 4.50, NULL, 8);

# Requests & Associated Tasks
# Posted by User Maria, has 2 associated tasks
INSERT INTO Request(posted_by, info, deadline, location_id)
	VALUES(1, "Get me some food.", '2018-12-24 00:00:01', 1);
INSERT INTO Task (info, status_code, bounty, task_in, location_id)  VALUES(
	"Quarter pounder", 0, 15.0, 1, 5);
INSERT INTO Task (info, status_code, bounty, task_in, location_id)  VALUES(
	"Fishballs", 0, 5.0, 1, 7);

# Posted by User Maria, has 2 associated tasks
INSERT INTO Request(posted_by, info, deadline, location_id)
	VALUES(1, "Get me some christmas supplies.", '2018-12-25 00:00:01', 2);
INSERT INTO Task (info, status_code, bounty, task_in, location_id)  VALUES(
	"Index cards", 0, 5.0, 2, 3);
INSERT INTO Task (info, status_code, bounty, task_in, location_id)  VALUES(
	"Christmas lights", 0, 5.0, 2, 6);

# Note that this Request and corresponding tasks has been disabled.
INSERT INTO Request(posted_by, info, deadline, location_id)
	VALUES(2, "Banned words", '2018-12-24 00:00:01', 2);
INSERT INTO Task (info, status_code, bounty, task_in, location_id)  VALUES(
	"Threats of violence", 0, 999.0, 3, 2);
UPDATE Request SET status_code=-1 WHERE id = 3;

# Posted by User Mary, has 3 associated tasks
INSERT INTO Request(posted_by, info, deadline, location_id)
	VALUES(3, "Get stuff I left at CS lib", '2018-12-25 00:00:01', 2);
INSERT INTO Task (info, status_code, bounty, task_in, location_id)  VALUES(
	"Umbrella near the guard", 0, 5.0, 4, 9);
INSERT INTO Task (info, status_code, bounty, task_in, location_id)  VALUES(
	"ID at the lower floow", 0, 5.0, 4, 9);

# Posted by User Madeline, has 1 associated task
INSERT INTO Request(posted_by, info, deadline, location_id)
	VALUES(2, "Org stuff", '2018-12-25 00:00:01', 2);
INSERT INTO Task (info, status_code, bounty, task_in, location_id)  VALUES(
	"Help with cleaning tambayan", 0, 5.0, 5, 4);

# have user Mike (id: 2) accept Task 1 (Request 1) and Task 3 (Request 2)
# This transaction is replicated in application logic (see accept_task.php)
DELIMITER |
START TRANSACTION;
UPDATE Task SET last_assigned=(SELECT id FROM User WHERE User.name="Mike") 
WHERE id = 1 OR id = 3;
UPDATE Task SET status_code=1
WHERE id = 1 OR id = 3;
COMMIT;
|
DELIMITER ;

SELECT * FROM Status;
SELECT * FROM ReportAction;
SELECT * FROM User;
SELECT * FROM Task;
SELECT * FROM Request;
SELECT * FROM Location;
