# Notepad Website

This was a very basic notepad system that is written in PHP. The main purpose was to have a private notepad system which held confidential notes on my own platform. There is still plenty of work required before someone would look to use this in a live environment. Features & live testing have been outlined below.
# Live Demo

https://hakeemsuleman.co.uk/projects/notepad - **Sign-up has been disabled on live demo.**

Please use the following details to login:

**Username:** test@hakeemsuleman.co.uk

**Password:** test

# Features

- Login system.
- Registration system.
- Saving up to 3 notes per user.
- Bootstrap.
- Stores notes into MySQL database.

# Pre installation

- Ensure that you modify dbconfig.php details to match your MySQL information.
- CREATE TABLE `users` (
  id int(11) NOT NULL,
  name text NOT NULL,
  email_address text NOT NULL,
  password text NOT NULL,
  subject1 text NOT NULL,
  text1 text NOT NULL,
  subject2 text NOT NULL,
  text2 text NOT NULL,
  subject3 text NOT NULL,
  text3 text NOT NULL
  )
- INSERT INTO `users` (`id`, `name`, `email_address`, `password`, `subject1`, `text1`, `subject2`, `text2`, `subject3`, `text3`) VALUES
  (1, 'TestUser', 'test@hakeemsuleman.co.uk', 'test', '', '', '', '', '', '')