drop database ecsite;

-- XAMPPのShellはShiftJISのため文字コードのミスマッチが発生しています
-- Shellにてコマンド操作する際には下記コマンドを実行し環境にあわせるとよいです

SET character_set_results = sjis;
SET character_set_client = sjis;

SHOW VARIABLES LIKE "chara%";

=====================================================================

-- 新しくデータベースを作成する
CREATE DATABASE ecsite CHARACTER SET utf8 COLLATE utf8_general_ci;;

-- データベースを選択する(切替)
USE ecsite





-- テーブルを作成する
CREATE TABLE users (
    user_id     VARCHAR(255)    PRIMARY KEY,
    password    VARCHAR(255)    NOT NULL,
    name        VARCHAR(32),
    address     VARCHAR(255)
);
CREATE TABLE categories (
    category_id INTEGER         PRIMARY KEY,
    name        VARCHAR(255)    NOT NULL
);
CREATE TABLE items (
    item_id     INTEGER         AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(128)    NOT NULL,
    manufacturer VARCHAR(32),
    category_id INTEGER         NOT NULL,
    color       VARCHAR(16),
    price       INTEGER         NOT NULL DEFAULT 0,
    stock       INTEGER         NOT NULL DEFAULT 0,
    recommended BOOLEAN         NOT NULL DEFAULT FALSE,
    FOREIGN KEY (category_id) REFERENCES categories (category_id)
);
CREATE TABLE items_in_cart (
    user_id     VARCHAR(255),
    item_id     INTEGER,
    amount      INTEGER         NOT NULL,
    booked_date DATE            NOT NULL,
    PRIMARY KEY (user_id, item_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id),
    FOREIGN KEY (item_id) REFERENCES items (item_id)
);
CREATE TABLE purchases (
    purchase_id INTEGER         AUTO_INCREMENT PRIMARY KEY,
    purchased_user  VARCHAR(255)    NOT NULL,
    purchased_date  DATE            NOT NULL,
    destination VARCHAR(255),
    cancel      BOOLEAN         NOT NULL DEFAULT FALSE,
    FOREIGN KEY (purchased_user) REFERENCES users (user_id)
);
CREATE TABLE purchases_details (
    purchase_detail_id  INTEGER AUTO_INCREMENT PRIMARY KEY,
    purchase_id INTEGER         NOT NULL,
    item_id     INTEGER         NOT NULL,
    amount      INTEGER         NOT NULL,
    FOREIGN KEY (purchase_id) REFERENCES purchases (purchase_id),
    FOREIGN KEY (item_id) REFERENCES items (item_id)
);
CREATE TABLE administrators (
    admin_id    VARCHAR(255)    PRIMARY KEY,
    password    VARCHAR(255)    NOT NULL,
    name        VARCHAR(32)
);

-- テーブルにデータを追加する
INSERT INTO administrators (admin_id, password, name) VALUES ('admin', 'admin', '管理者');

INSERT INTO categories (category_id, name) VALUES (0, 'すべて');
INSERT INTO categories (category_id, name) VALUES (1, '帽子');
INSERT INTO categories (category_id, name) VALUES (2, '鞄');

INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('麦わら帽子', '日本帽子製造', 1, '黄色', 4980, 12, FALSE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('ストローハット', '(株)ストローハットジャパン', 1, '茶色', 3480, 15, TRUE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('子ども用麦わら帽子', '東京帽子店', 1, '赤色', 2980, 3, FALSE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('ストローハット PART2', '(株)ストローハットジャパン', 1, '青色', 4480,6, FALSE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('野球帽', '日本帽子製造', 1, '緑色', 2500, 17, TRUE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('ニットキャップ', '日本帽子製造', 1, '紺色', 1800, 9, FALSE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('ハンチング帽', '日本帽子製造', 1, '黄色', 1980, 20, FALSE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('ストローハット PART3', '(株)ストローハットジャパン', 1, '茶色', 5480, 2, TRUE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('ターバン', '東京帽子店', 1, '赤色', 4580, 1, FALSE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('ベレー帽', '東京帽子店', 1, '青色', 3200, 8, FALSE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('マジック用ハット', '東京帽子店', 1, '緑色', 650, 17, TRUE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('鞄A', '東京鞄店', 2, '青色', 1980, 18, TRUE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('鞄B', '東京鞄店', 2, '緑色', 4980, 15, FALSE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('鞄E', '(株)鞄', 2, '紺色', 2200, 3, FALSE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('鞄G', '日本鞄製造', 2, '黄色', 2980, 6, FALSE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('鞄H', '日本鞄製造', 2, '茶色', 780, 17, TRUE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('鞄F', '(株)鞄', 2, '赤色', 2500, 9, TRUE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('鞄C', '東京鞄店', 2, '青色', 1800, 20, TRUE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('鞄D', '東京鞄店', 2, '緑色', 1980, 2, FALSE);
INSERT INTO items (name, manufacturer,category_id, color, price, stock, recommended) VALUES ('鞄I', '日本鞄製造', 2, '茶色', 690, 1, FALSE);

SELECT * FROM users;
SELECT * FROM categories;
SELECT * FROM items;
SELECT * FROM items_in_cart;
SELECT * FROM purchases;
SELECT * FROM purchases_details;
SELECT * FROM administrators;