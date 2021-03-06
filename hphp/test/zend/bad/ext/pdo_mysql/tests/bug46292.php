<?php	
	
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mysql_pdo_test.inc');
	$pdoDb = MySQLPDOTest::factory();
	

	class myclass implements Serializable {
		public function __construct() {
			printf("%s()\n", __METHOD__);
		}
		
		public function serialize() {
			printf("%s()\n", __METHOD__);
			return "any data from serialize()";
		}
		
		public function unserialize($dat) {
			printf("%s(%s)\n", __METHOD__, var_export($dat, true));
			return $dat;
		}
	}

	class myclass2 extends myclass { }

	$pdoDb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$pdoDb->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);
	
	$pdoDb->query('DROP TABLE IF EXISTS testz');
	
	$pdoDb->query('CREATE TABLE testz (name VARCHAR(20) NOT NULL, value INT)');
	
	$pdoDb->query("INSERT INTO testz VALUES ('myclass', 1), ('myclass2', 2), ('myclass', NULL), ('myclass3', NULL)");

	$stmt = $pdoDb->prepare("SELECT * FROM testz");

	var_dump($stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE | PDO::FETCH_GROUP));
	$stmt->execute();

	var_dump($stmt->fetch());
	var_dump($stmt->fetch());
	var_dump($stmt->fetchAll());
?><?php
require dirname(__FILE__) . '/mysql_pdo_test.inc';
$db = MySQLPDOTest::factory();
$db->exec('DROP TABLE IF EXISTS testz');
?>