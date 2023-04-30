CREATE TABLE IF NOT EXISTS up_tutortoday_user_role (
    USER_ID int unsigned auto_increment not null,
    ROLE_ID int not null,
    PRIMARY KEY (USER_ID),
    INDEX up_tutortoday_users_roles_index (ROLE_ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_user_ed_format (
    USER_ID int unsigned auto_increment not null,
    EDUCATION_FORMAT_ID int not null,
    PRIMARY KEY (USER_ID, EDUCATION_FORMAT_ID),
    INDEX up_tutortoday_users_ed_format_index (EDUCATION_FORMAT_ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_user_description (
    USER_ID int unsigned auto_increment not null,
    DESCRIPTION text,
    PRIMARY KEY (USER_ID)
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

CREATE TABLE IF NOT EXISTS up_tutortoday_vk (
    ID int unsigned auto_increment not null,
    USER_ID int unsigned not null,
    VK_PROFILE varchar(100),
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_telegram(
    ID int unsigned auto_increment not null,
    USER_ID int unsigned not null,
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
    PRICE int unsigned default 0,
    PRIMARY KEY (USER_ID, SUBJECT_ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_profile_images (
    ID int unsigned auto_increment not null,
    USER_ID int unsigned not null,
    LINK varchar(255) not null,
    WIDTH int unsigned,
    HEIGHT int unsigned,
    EXTENSION varchar(10),
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

CREATE TABLE IF NOT EXISTS up_tutortoday_cities (
    ID int unsigned auto_increment not null,
    NAME varchar(60) not null,
    PRIMARY KEY (ID)
);