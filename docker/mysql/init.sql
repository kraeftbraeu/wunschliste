CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created DATETIME,
    changed DATETIME,
    status VARCHAR(1),
    name TEXT,
    email TEXT,
    password TEXT,
    isadmin BOOLEAN
);
CREATE TABLE IF NOT EXISTS wish (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created DATETIME,
    changed DATETIME,
    status VARCHAR(1),
    wisher INT,
    description TEXT,
    gotdate DATETIME
);
CREATE TABLE IF NOT EXISTS present (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created DATETIME,
    changed DATETIME,
    status VARCHAR(1),
    wisher INT,
    presenter INT,
    description TEXT,
    gotdate DATETIME
);

INSERT INTO user
    (id, created, changed, status, name, email, password, isadmin)
VALUES
    (1, NOW(), NOW(), 'A', 'Anton',   '', '$2y$10$YWd/Sy6K.hwybg9tFPF.p.0c6UDChgNHiJubSEeDFb3zV0znbkUDu', true), --  pw: a
    (2, NOW(), NOW(), 'A', 'Beppi',   '', '$2y$10$4aMlwHU9xl8jd2uUwKCWgefnnC5sfaO9Vk5FI.NkuanIArV2ZCa92', false), -- pw: b 
    (3, NOW(), NOW(), 'A', 'Christl', '', '$2y$10$yabe8.iPMKw/JkxMNdszq.gE0huTCgG9I4KfbmzT9kFmwsIXK18ZG', false), -- pw: c 
    (4, NOW(), NOW(), 'A', 'Donisl',  '', '$2y$10$P9c7l9Bz2cquVXyvjJFQCOQx7/O.2U6yevY5psoXY/Z.7zheVPakG', false); -- pw: d 
    
INSERT INTO wish
    (id, created, changed, status, wisher, description, gotdate)
VALUES
    (1, NOW(), NOW(), 'A', 1, 'Irgendwas, hier der Link: https://google.com', null),
    (2, NOW(), NOW(), 'A', 1, 'Irgendwas anderes', null),
    (3, NOW(), NOW(), 'A', 2, 'Was fürn Beppi', null),
    (4, NOW(), NOW(), 'A', 3, 'Was fürd Christl', null);
    
INSERT INTO present
    (id, created, changed, status, wisher, presenter, description, gotdate)
VALUES
    (1, NOW(), NOW(), 'A', 1, 2, 'Irgendwas anderes', null),
    (2, NOW(), NOW(), 'A', 3, 1, 'Was fürd Christl', null);