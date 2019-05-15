create database api_rest_alcohol;

use api_rest_alcohol;

CREATE TABLE IF NOT EXISTS category(
    id      int(255) AUTO_INCREMENT not null,
    name    varchar(255),

    CONSTRAINT pk_category PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS product (
    id              int(255) AUTO_INCREMENT not null,
    category_id     int(255) not null,
    name            varchar(255) not null,
    description     TEXT,
    price           decimal(6,2) not null,
    size            varchar(100),
    image           varchar(255),

    CONSTRAINT pk_product PRIMARY KEY(id),
    CONSTRAINT fk_category_product FOREIGN KEY (category_id) REFERENCES category(id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS user(
    id              int(255) AUTO_INCREMENT not null,
    name            varchar(255) not null,
    surname         varchar(255),
    dni             int(8),
    email           varchar(255),
    phone           int(9)
    adress          varchar(255),

    CONSTRAINT pk_user PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order(
    id              int(255) AUTO_INCREMENT not null,
    user_id         int(255) not null,
    product_id      int(255) not null,
    price           decimal(6,2) not null,
    money           decimal(6,2) not null,
    change          decimal(6,2) not null,

   CONSTRAINT pk_order PRIMARY KEY(id),
   CONSTRAINT fk_user_order FOREIGN KEY (user_id) REFERENCES user(id),
   CONSTRAINT fk_product_order FOREIGN KEY (product_id) REFERENCES product(id)
)ENGINE=InnoDB;