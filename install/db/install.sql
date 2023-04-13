CREATE TABLE IF NOT EXISTS up_tutortoday_user (
    ID int unsigned auto_increment not null,
    NAME varchar(100) not null,
    SURNAME varchar(100) not null,
    MIDDLE_NAME varchar(100),
    CITY varchar(100),
    EDUCATION_FORMAT_ID int not null,
    ROLE_ID int not null,
    SUBJECT_ID int,
    PRIMARY KEY (ID),
    INDEX up_tutortoday_tutors_subjects (SUBJECT_ID),
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
    TUTOR_ID int unsigned not null,
    PHONE_NUMBER varchar(16),
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