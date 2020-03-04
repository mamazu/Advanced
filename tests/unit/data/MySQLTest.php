<?php
/**
 * 
 * Advanced microFramework
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * 
 * @copyright Copyright (c) 2019 Advanced microFramework
 * @author    Advanced microFramework Team (Denzel Code, Soull Darknezz)
 * @link https://github.com/DenzelCode/Advanced
 * 
 */

namespace tests\unit\data;

use advanced\config\Config;
use advanced\data\sql\MySQL;
use advanced\project\Project;
use Exception;
use tests\TestCase;

class MySQLTest extends TestCase {

    public function __construct() {
        parent::__construct();

        $config = new Config(Project::getConfigPath());

        $config->setIfNotExists("database", [
            "host" => "127.0.0.1",
            "port" => 3306,
            "username" => "root",
            "password" => "",
            "database" => "unittesting"
        ])->saveIfModified();

        Project::setSQL(new MySQL(
            $config->get("database.host"), 
            $config->get("database.port"), 
            $config->get("database.username"), 
            $config->get("database.password"), 
            $config->get("database.database")
        ));
    }
    
    public function testCreateTable() : void {
        $sql = Project::getSQL();

        $query = $sql->create()->table("test")->columns([
            "id" => "int(11) PRIMARY KEY AUTO_INCREMENT",
            "name" => "varchar(255)"
        ]);

        $this->assertTrue($query->execute());
    }

    public function testInsert() : void {
        $sql = Project::getSQL();

        $query = $sql->insert()->table("test")->fields([
            "name" => "Testing"
        ]);

        $this->assertTrue($query->execute());
    }

    public function testSelect() : void {
        $sql = Project::getSQL();

        $query = $sql->select()->table("test")->where("name = ?", ["Testing"])->execute();

        $data = $query->fetch();

        $this->assertEquals($data["name"], "Testing");
    }

    public function testUpdate() : void {
        $sql = Project::getSQL();

        $find = "Testing";

        $execute = $sql->update()->table("test")->field("name", "Replacement")->where("name = ?", [$find])->execute();

        $this->assertTrue($execute);
    }

    public function testDelete() : void {
        $sql = Project::getSQL();

        $find = "Replacement";

        $execute = $sql->delete()->table("test")->where("name = ?", [$find])->execute();

        $this->assertTrue($execute);
    }

    public function testAddColumns() : void {
        $sql = Project::getSQL();

        $execute = $sql->addColumns()->table("test")->column("last_name", "varchar(255)")->execute();

        $this->assertTrue($execute);
    }

    public function testDropColumns() : void {
        $sql = Project::getSQL();

        $execute = $sql->dropColumns()->table("test")->column("last_name")->execute();

        $this->assertTrue($execute);
    }

    public function testShowColumns() : void {
        $sql = Project::getSQL();

        $execute = $sql->showColumns()->table("test")->execute();

        $this->assertIsArray($execute->fetchAll());
    }

    public function testTruncate() : void {
        $sql = Project::getSQL();

        $execute = $sql->truncate()->table("test")->execute();

        $this->assertTrue($execute);
    }

    public function testDropTable() : void {
        $sql = Project::getSQL();

        $execute = $sql->drop()->table("test")->execute();

        $this->assertTrue($execute);
    }
}
