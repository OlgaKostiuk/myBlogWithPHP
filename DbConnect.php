<?php

class DbConnect
{
    private static $db = null;

    public function GetDb()
    {
        if (self::$db != null) {
            return self::$db;
        } else {
            try {
                self::$db = new PDO("mysql:host=localhost;port=52355;charset=utf8", "azure", "6#vWHD_$");
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$db->exec("CREATE DATABASE IF NOT EXISTS blog DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci");

                self::$db->exec("USE blog");

                self::$db->exec("CREATE TABLE IF NOT EXISTS category(
                                        id INT NOT NULL AUTO_INCREMENT,
                                        title VARCHAR(255) NOT NULL,
                                        PRIMARY KEY(id)
                                        )");

                self::$db->exec("CREATE TABLE IF NOT EXISTS post(
                                        id INT NOT NULL AUTO_INCREMENT,
                                        title TEXT NOT NULL,
                                        description TEXT NOT NULL,
                                        login VARCHAR (255) NOT NULL,
                                        picture VARCHAR(255),
                                        date DATETIME NOT NULL,
                                        category_id INT NOT NULL,
                                        PRIMARY KEY(id),
                                        FOREIGN KEY(category_id) REFERENCES category(id),
                                        FULLTEXT (title)
                                        )");

                $count = self::$db->prepare("SELECT COUNT(*) FROM category");
                $count->execute();
                if ($count->fetch()[0] == 0) {
                    self::$db->exec("INSERT INTO category(title) VALUES ('Funny')");
                    self::$db->exec("INSERT INTO category(title) VALUES ('Movies and TV')");
                    self::$db->exec("INSERT INTO category(title) VALUES ('Books')");
                    self::$db->exec("INSERT INTO category(title) VALUES ('Sport')");
                    self::$db->exec("INSERT INTO category(title) VALUES ('Cars')");
                }
                return self::$db;
            } catch (PDOException $ex) {
                echo $ex->getMessage();
            }
        }
    }
}