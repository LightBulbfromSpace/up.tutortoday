CREATE TABLE IF NOT EXISTS up_tutortoday_user (
    ID int unsigned auto_increment not null,
    PASSWORD varchar(100) not null,
    NAME varchar(100) not null,
    SURNAME varchar(100) not null,
    MIDDLE_NAME varchar(100),
    DESCRIPTION text,
    CITY varchar(100),
    EDUCATION_FORMAT_ID int not null,
    ROLE_ID int not null,
    PRIMARY KEY (ID),
    INDEX up_tutortoday_users_roles (ROLE_ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_education_format (
    ID int unsigned auto_increment not null,
    NAME varchar(100) not null,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_roles (
    ID int unsigned auto_increment not null,
    NAME varchar(100) not null,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_subject (
    ID int unsigned auto_increment not null,
    NAME varchar(100) not null,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_contacts (
    ID int unsigned auto_increment not null,
    USER_ID int unsigned not null,
    PHONE_NUMBER varchar(20),
    EMAIL varchar(255),
    VK_PROFILE varchar(100),
    TELEGRAM_USERNAME varchar(32),
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_feedbacks (
    ID int unsigned auto_increment not null,
    TUTOR_ID int unsigned not null,
    TITLE varchar(100) not null,
    DESCRIPTION text,
    STARS_NUMBER int,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_user_subject (
    USER_ID int unsigned not null,
    SUBJECT_ID int unsigned not null,
    PRICE int unsigned,
    PRIMARY KEY (USER_ID, SUBJECT_ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_profile_images (
    ID int unsigned auto_increment not null,
    USER_ID int unsigned not null,
    LINK varchar(255) not null,
    WIDTH int unsigned not null,
    HEIGHT int unsigned not null,
    EXTENSION varchar(10) not null,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_free_time (
    ID int unsigned auto_increment not null,
    USER_ID int unsigned not null,
    WEEKDAY_ID int unsigned not null,
    START datetime not null,
    END datetime not null,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_weekdays (
    ID int unsigned auto_increment not null,
    NAME varchar(30) not null,
    PRIMARY KEY (ID)
);