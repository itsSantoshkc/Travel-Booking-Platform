CREATE DATABASE IF NOT EXISTS travelbook;


CREATE TABLE User (
    userID VARCHAR(36) PRIMARY KEY,
    firstName VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    role VARCHAR(15),
    dateOfBirth DATE
);

CREATE TABLE User_Verification (
    userId VARCHAR(36),
    verifiedAt DATETIME,
    token VARCHAR(6),
    verified TINYINT(1),
    FOREIGN KEY (userId) REFERENCES User(userID) ON DELETE CASCADE
);

-- Travel Package 

CREATE TABLE travelPackages (
    package_id VARCHAR(100) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    arrivalTime TIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    duration INT,
    starting_date DATE,
    location VARCHAR(255),
    price DECIMAL(10, 2),
    description TEXT
    totalSlots INT
);

CREATE TABLE package_images (
    image_id VARCHAR(100) PRIMARY KEY,
    package_id VARCHAR(100),
    image_path VARCHAR(255),
    FOREIGN KEY (package_id) REFERENCES travelPackages(package_id) ON DELETE CASCADE
);



-- BOOKING 

    CREATE TABLE Booking (
    booking_id VARCHAR(100) PRIMARY KEY,
    user_id VARCHAR(36),
    package_id VARCHAR(100),
    no_of_slots INT,
    booked_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(userID),
    FOREIGN KEY (package_id) REFERENCES travelPackages(package_id)
);

CREATE TABLE Reviews (
    review_id VARCHAR(100) PRIMARY KEY,
    review VARCHAR(500),
    user_id VARCHAR(36),
    rating INT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    package_id VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES User(userID),
    FOREIGN KEY (package_id) REFERENCES travelPackages(package_id)
);
    

-- ALTER TABLE `package_images`
--   DROP PRIMARY KEY,
--    ADD PRIMARY KEY(
--      `image_id`,
--      `package_id`
--    );


-- Mock Data

INSERT INTO travelPackages (package_id, name, arrivalTime, created_at, duration, starting_date, location, price, description)
VALUES 
('PKG-001', 'Alpine Escape', '2026-06-15', NOW(), 7, '2026-06-08', 'Swiss Alps, Switzerland', 1250.00, 'A breathtaking 7-day retreat in the heart of the Alps. Includes guided hiking and luxury chalet stay.'),
('PKG-002', 'Tropical Zen', '2026-08-20', NOW(), 5, '2026-08-15', 'Bali, Indonesia', 850.00, 'Rejuvenate your soul with daily yoga sessions, spa treatments, and private beach access.'),
('PKG-003', 'Kyoto Cultural Trail', '2026-04-10', NOW(), 10, '2026-03-31', 'Kyoto, Japan', 2100.00, 'Experience the cherry blossoms while visiting ancient temples and participating in tea ceremonies.'),
('PKG-004', 'Sahara Starry Night', '2026-11-05', NOW(), 4, '2026-11-01', 'Merzouga, Morocco', 600.00, 'Camel trekking through the dunes with an overnight stay in a traditional Berber desert camp.'),
('PKG-005', 'Amalfi Coast Luxury', '2026-07-12', NOW(), 6, '2026-07-06', 'Positano, Italy', 3200.00, 'Luxury boat tours, fine dining, and stunning coastal views in one of Italy\'s most iconic spots.'),
('PKG-006', 'Santorini Sunset', '2026-09-05', NOW(), 6, '2026-08-30', 'Oia, Greece', 1850.00, 'Stay in a traditional white-washed villa with a private infinity pool overlooking the Aegean Sea.'),
('PKG-007', 'Icelandic Aurora', '2026-12-15', NOW(), 5, '2026-12-10', 'Reykjavik, Iceland', 2200.00, 'Hunt the Northern Lights and soak in the Blue Lagoon. Includes a 4x4 Golden Circle tour.'),
('PKG-008', 'Patagonia Expedition', '2026-11-20', NOW(), 12, '2026-11-08', 'Torres del Paine, Chile', 2900.00, 'A rugged trekking adventure through granite peaks, glaciers, and turquoise lakes.'),
('PKG-009', 'Parisian Romance', '2026-02-14', NOW(), 4, '2026-02-10', 'Paris, France', 1400.00, 'Valentine\'s special: Seine river cruise, Eiffel Tower dinner, and boutique hotel in Montmartre.'),
('PKG-010', 'Serengeti Safari', '2026-07-22', NOW(), 8, '2026-07-14', 'Serengeti, Tanzania', 3500.00, 'Witness the Great Migration. Luxury tented camps and sunrise hot air balloon rides included.'),
('PKG-011', 'Tokyo Neon Lights', '2026-10-10', NOW(), 7, '2026-10-03', 'Shinjuku, Japan', 1950.00, 'Explore the high-tech side of Japan. Includes robot cafe entry and a trip to Mt. Fuji.'),
('PKG-012', 'Amazon Rainforest', '2026-05-18', NOW(), 6, '2026-05-12', 'Manaus, Brazil', 1100.00, 'Deep jungle immersion. Jungle canopy walks and piranha fishing with local guides.'),
('PKG-013', 'New York City Lights', '2026-12-28', NOW(), 5, '2026-12-23', 'Manhattan, USA', 1700.00, 'Christmas in NYC. Includes Broadway tickets and ice skating at Rockefeller Center.'),
('PKG-014', 'Great Barrier Reef', '2026-01-15', NOW(), 7, '2026-01-08', 'Cairns, Australia', 2300.00, 'Private catamaran tour with diving and snorkeling among the world\'s largest coral reef.'),
('PKG-015', 'Prague Gothic Charm', '2026-09-12', NOW(), 4, '2026-09-08', 'Prague, Czech Republic', 750.00, 'Walk through history in the City of a Hundred Spires. Beer tasting and castle tours included.');

INSERT INTO package_images (image_id, package_id, image_path)
VALUES 
('IMG-001', 'PKG-001', 'https://picsum.photos/id/1016/800/600'), -- Mountain view
('IMG-002', 'PKG-001', 'https://picsum.photos/id/1036/800/600'), -- Winter cabin
('IMG-003', 'PKG-002', 'https://picsum.photos/id/1015/800/600'), -- River/Tropical
('IMG-004', 'PKG-002', 'https://picsum.photos/id/1043/800/600'), -- Beach/Ocean
('IMG-005', 'PKG-003', 'https://picsum.photos/id/1050/800/600'), -- Japanese Architecture
('IMG-006', 'PKG-004', 'https://picsum.photos/id/1029/800/600'), -- Desert/Sand dunes
('IMG-008', 'PKG-006', 'https://picsum.photos/id/1014/800/600'), -- Coastal/Greece
('IMG-009', 'PKG-007', 'https://picsum.photos/id/1020/800/600'), -- Ice/Northern
('IMG-010', 'PKG-008', 'https://picsum.photos/id/1033/800/600'), -- Mountains
('IMG-011', 'PKG-009', 'https://picsum.photos/id/1044/800/600'), -- European Street
('IMG-012', 'PKG-010', 'https://picsum.photos/id/1024/800/600'), -- Wildlife/Nature
('IMG-013', 'PKG-011', 'https://picsum.photos/id/1037/800/600'), -- City/Night
('IMG-014', 'PKG-012', 'https://picsum.photos/id/1052/800/600'), -- Jungle/River
('IMG-015', 'PKG-013', 'https://picsum.photos/id/1059/800/600'), -- NYC Skyline
('IMG-016', 'PKG-014', 'https://picsum.photos/id/1067/800/600'), -- Tropical Reef
('IMG-017', 'PKG-015', 'https://picsum.photos/id/1070/800/600'), -- Old Town Architecture
('IMG-007', 'PKG-005', 'https://picsum.photos/id/1011/800/600'); -- Italian Coastline