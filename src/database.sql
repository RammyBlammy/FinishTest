CREATE SCHEMA IF NOT EXISTS testov;
    CREATE TABLE IF NOT EXISTS testov.posts (
		user_id int,
		id int primary key, 
		title text, 
		body text
	);
	CREATE TABLE IF NOT EXISTS testov.comments (
		post_id int REFERENCES testov.posts(id), 
		id int primary key, 
		name text, 
		email text, 
		body text
	)