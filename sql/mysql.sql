CREATE TABLE bans (
    created      TIMESTAMP(14) NOT NULL,
    userid       INT(11)     DEFAULT NULL,
    banneduserid INT(11)     DEFAULT NULL,
    roomid       INT(11)     DEFAULT NULL,
    ip           VARCHAR(16) DEFAULT NULL,
    KEY userid (userid),
    KEY created (created)
)
    ENGINE = ISAM;

CREATE TABLE connections (
    id       VARCHAR(32)   NOT NULL DEFAULT '',
    updated  TIMESTAMP(14) NOT NULL,
    created  TIMESTAMP(14) NOT NULL,
    userid   INT(11)                DEFAULT NULL,
    roomid   INT(11)                DEFAULT NULL,
    state    TINYINT(4)    NOT NULL DEFAULT '1',
    color    INT(11)                DEFAULT NULL,
    start    INT(11)                DEFAULT NULL,
    lang     CHAR(2)                DEFAULT NULL,
    ip       VARCHAR(16)            DEFAULT NULL,
    tzoffset INT(11)                DEFAULT '0',
    PRIMARY KEY (id),
    KEY userid (userid),
    KEY roomid (roomid),
    KEY updated (updated)
)
    ENGINE = ISAM;

CREATE TABLE ignors (
    created       TIMESTAMP(14) NOT NULL,
    userid        INT(11) DEFAULT NULL,
    ignoreduserid INT(11) DEFAULT NULL,
    KEY userid (userid),
    KEY ignoreduserid (ignoreduserid),
    KEY created (created)
)
    ENGINE = ISAM;

CREATE TABLE messages (
    id       INT(11)       NOT NULL AUTO_INCREMENT,
    created  TIMESTAMP(14) NOT NULL,
    toconnid VARCHAR(32)            DEFAULT NULL,
    touserid INT(11)                DEFAULT NULL,
    toroomid INT(11)                DEFAULT NULL,
    command  VARCHAR(255)  NOT NULL DEFAULT '',
    userid   INT(11)                DEFAULT NULL,
    roomid   INT(11)                DEFAULT NULL,
    txt      TEXT,
    PRIMARY KEY (id),
    KEY touserid (touserid),
    KEY toroomid (toroomid),
    KEY toconnid (toconnid),
    KEY created (created)
)
    ENGINE = ISAM;

CREATE TABLE rooms (
    id          INT(11)       NOT NULL AUTO_INCREMENT,
    updated     TIMESTAMP(14) NOT NULL,
    created     TIMESTAMP(14) NOT NULL,
    name        VARCHAR(32)   NOT NULL DEFAULT '',
    ispublic    CHAR(1)                DEFAULT NULL,
    ispermanent CHAR(1)                DEFAULT NULL,
    PRIMARY KEY (id),
    KEY name (name),
    KEY ispublic (ispublic),
    KEY ispermanent (ispermanent),
    KEY updated (updated)
)
    ENGINE = ISAM;

INSERT INTO rooms
VALUES (1, 20040222014206, 20040205044554, 'The Lounge', 'y', '1');
INSERT INTO rooms
VALUES (2, 20040219232630, 20040205044554, 'Hollywood', 'y', '2');
INSERT INTO rooms
VALUES (3, 20040219232631, 20040205044554, 'Tech Talk', 'y', '3');
INSERT INTO rooms
VALUES (4, 20040216035558, 20040205044554, 'Current Events', 'y', '4');
