CREATE TABLE activity (
    activity_id VARCHAR(100) PRIMARY KEY,
    name VARCHAR(100),
    description TEXT, -- Changed to TEXT for longer descriptions
    no_of_slots INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    price DECIMAL(10, 2), -- Allows values up to 99,999,999.99
    event_type TINYINT,
    location VARCHAR(255),
    starting_date DATE
);  


CREATE TABLE activity_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY, -- Added a unique PK for the image record
    image_path VARCHAR(255) NOT NULL,
    activity_id VARCHAR(100), -- Must match the type of activity(activity_id) exactly
    FOREIGN KEY (activity_id) REFERENCES activity(activity_id) ON DELETE CASCADE
);


CREATE TABLE days (
    day_id INT AUTO_INCREMENT PRIMARY KEY, -- Added a unique PK for the day record
    name VARCHAR(10), -- Must match the type of activity(activity_id) exactly
);

INSERT INTO days (name) VALUES 
('Sunday'),
('Monday'),
('Tuesday'),
('Wednesday'),
('Thursday'),
('Friday'),
('Saturday');

CREATE TABLE activity_days (
    day_id INT , -- Added a unique PK for the day record
    activity_id VARCHAR(100), -- Must match the type of activity(activity_id) exactly
    FOREIGN KEY (activity_id) REFERENCES activity(activity_id) ON DELETE CASCADE
    FOREIGN KEY (day_id) REFERENCES days(day_id) ON DELETE CASCADE
);

CREATE TABLE activity_slots (
    time_slots string,
    activity_id VARCHAR(100), -- Must match the type of activity(activity_id) exactly
    FOREIGN KEY (activity_id) REFERENCES activity(activity_id) ON DELETE CASCADE
);

CREATE TABLE reviews (
    userId VARCHAR(36) NOT NULL,
    activityID VARCHAR(100) NOT NULL,
    rating INT NOT NULL,
    review TEXT NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES user(userID),
    FOREIGN KEY (activityID) REFERENCES activity(activity_id)
);


-- BOOKING 

CREATE TABLE booking(
    booking_id VARCHAR(100) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL, 
    activity_id VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(userID), 
    FOREIGN KEY (activity_id) REFERENCES activity(activity_id), 
    no_of_slots INT NOT NULL, 
    time VARCHAR(15), 
    booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    booked_for DATE 
    )
    






