-- Create a database named 'Register' if it doesn't already exist.
CREATE DATABASE IF NOT EXISTS Register;

-- Switch to the 'Register' database for subsequent operations.
USE Register;

-- Retrieve all records from the users table
SELECT * FROM users;

-- Create a table named 'users' if it doesn't already exist, with columns for user ID, username, password, and balance.
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Unique identification number for each user.
    username VARCHAR(20) NOT NULL,          -- Name used to identify each user.
    password VARCHAR(255) NOT NULL,         -- Secret code used for user authentication.
    balance DECIMAL(10, 2) DEFAULT 0.0     -- Amount of money associated with each user.
);

-- Modify the 'users' table to ensure that the password column can hold longer passwords.
ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NOT NULL;

-- Create a table named 'transaction_history' if it doesn't already exist, with columns for transaction ID, user ID, transaction date, transaction type, and amount.
CREATE TABLE IF NOT EXISTS transaction_history (
    id INT AUTO_INCREMENT PRIMARY KEY,                      -- Unique identification number for each transaction.
    user_id INT,                                             -- Identification number of the user involved in the transaction.
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,   -- Date and time when the transaction occurred.
    transaction_type ENUM('top_up', 'transfer', 'payment'), -- Type of transaction, either a 'top-up', 'transfer' or 'payment'.
    amount DECIMAL(10, 2),                                   -- Amount of money involved in the transaction.
    FOREIGN KEY (user_id) REFERENCES users(id)              -- Relationship between the user ID in this table and the 'id' column in the 'users' table.
);

-- Add a column to the 'transaction_history' table to store the purpose of each transfer.
ALTER TABLE transaction_history ADD COLUMN purpose_of_transfer VARCHAR(255);

-- Add a column to the 'transaction_history' table to store the description of each transaction.
ALTER TABLE transaction_history ADD COLUMN description VARCHAR(255);

-- Query to fetch transaction history where the user is either the sender or the recipient, ordered by the transaction date in descending order.
SELECT t.id,                                               -- Transaction ID
       t.user_id,                                          -- User ID associated with the transaction
       t.transaction_date,                                 -- Date and time of the transaction
       t.transaction_type,                                 -- Type of transaction (top-up, transfer, payment)
       t.amount,                                           -- Amount involved in the transaction
       t.purpose_of_transfer,                              -- Purpose of the transfer (if available)
       t.description,                                      -- Description of the transaction (if available)
       CASE                                                -- Determine the recipient username based on transaction type
           WHEN t.transaction_type = 'top_up' THEN 'Owner' -- If top-up, the recipient is the owner
           WHEN t.transaction_type = 'payment' THEN 'Cards'-- If payment, the recipient is 'Cards'
           ELSE recipient.username                         -- For transfers, use recipient's username
       END AS recipient_username,   
       u.username AS user_name                             -- Username of the user initiating the transaction
FROM transaction_history t                                -- Alias 't' for the transaction_history table
LEFT JOIN users recipient ON t.recipient_id = recipient.id -- Joining with users table to get recipient's information
LEFT JOIN users u ON t.user_id = u.id                     -- Joining with users table to get user's information
WHERE t.user_id = user_id OR t.sender_id = user_id        -- Filtering based on user_id or sender_id
ORDER BY t.transaction_date DESC;                         -- Sorting the results by transaction_date in descending order
