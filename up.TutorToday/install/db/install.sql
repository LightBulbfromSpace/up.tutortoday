CREATE TABLE IF NOT EXISTS up_tutortoday_user (
    ID bigint auto_increment not null,
    NAME varchar(63) not null,
    SURNAME varchar(63) not null,
    MIDDLE_NAME varchar(63),
    ROLE_ID int not null,
    SUBJECT_ID int,
    PRIMARY KEY (ID)
);

CREATE INDEX up_tutortoday_tutors_subjects
    ON up_tutortoday_user (SUBJECT_ID);

CREATE INDEX up_tutortoday_users_roles
    ON up_tutortoday_user (ROLE_ID);

CREATE TABLE IF NOT EXISTS up_tutortoday_roles (
    ID int auto_increment not null,
    NAME varchar(63) not null,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_subject (
    ID int auto_increment not null,
    NAME varchar(63) not null,
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_contacts (
    ID bigint auto_increment not null,
    TUTOR_ID bigint not null,
    PHONE_NUMBER varchar(16),
    EMAIL varchar(320),
    VK_PROFILE varchar(100),
    TELEGRAM_USERNAME varchar(32),
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS up_tutortoday_feedbacks (
    ID bigint auto_increment not null,
    TUTOR_ID bigint not null,
    TITLE varchar(100) not null,
    DESCRIPTION varchar(500),
    STARS_NUMBER int,
    PRIMARY KEY (ID)
);