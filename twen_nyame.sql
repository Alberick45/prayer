create database twen_nyame;
use twen_nyame;

### 1. *Users*   Stores user account information.
  

create table users (
user_id int primary key auto_increment, 
fullname varchar(40)  not null,
username varchar(40) unique not null,
email varchar(40),
phone varchar(30) check(phone like '+%'),
password varchar(60) not null,
profile_picture blob,
bio text,
date_joined date,
last_login datetime 
);
### 2. *Prayers*   Stores individual prayers made by users.
   

create table prayers (
prayer_id int primary key auto_increment, 
user_id int not null,
prayer_title varchar(40),
prayer_body text,
prayer_type enum('Gratitude','Request','Answered','Apology'),
created_at date,
is_public boolean,
status enum('Pending','Answered')
);

### 3. *Prayer Requests*  Stores prayer requests submitted by users.
  


create table prayer_requests (
request_id int primary key auto_increment, 
user_id int not null,
request_body text,
prayer_type enum('Gratitude','Request','Answered','Apology'),
created_at date,
is_public boolean,
status enum('Pending','Answered')
);

### 4. *Groups*   Stores prayer groups that users can create or join.
   


create table prayer_group (
group_id int primary key auto_increment, 
group_name varchar(40) unique not null,
description text,
created_by int,
created_at date,
is_private boolean
);

### 5. *Group Members*   Tracks membership of users in various prayer groups.
  


create table group_members (
membership_id int primary key auto_increment, 
group_id int not null, 
user_id int not null,
joined_at date
);

### 6. *Messages*   Stores messages or prayer requests sent via the inbox (similar to WhatsApp).
  

create table messages (
message_id int primary key auto_increment, 
sender_id int not null, 
receiver_id int not null,
message_body text,
sent_at date
);

### 7. *Prayer Journals*   Stores personal prayer journal entries for users.
  
create table prayer_journals (
journal_id int primary key auto_increment, 
user_id int not null, 
entry_body text,
created_at date
);

### 8. *Devotionals*   Stores daily devotionals and Bible readings.
   

create table devotionals (
devotional_id int primary key auto_increment, 
title varchar(40) unique not null,
content text,
created_by int not null,
created_at date
);

### 9. *Zoom Meetings* Stores information about Zoom prayer meetings.
  
create table zoom_meetings (
meeting_id int primary key auto_increment, 
host_id int not null, 
meeting_title varchar(40) unique not null,
zoom_link text,
start_time date,
created_at date
);

### 10. *Music*  Stores uploaded music files categorized by emotion or theme.
   

create table music (
music_id int primary key auto_increment, 
user_id int not null, 
file_path text,
category enum('Gratitude','Sorrow','Joy'),

uploaded_at date
);

### 11. Friendships  Stores friendships and friend requests between users.
  
create table friendships (
friendship_id int primary key auto_increment, 
user_id_1 int not null, 
user_id_2 int not null,
status enum('Pending','Accepted','Blocked')

);