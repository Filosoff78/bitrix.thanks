<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use Bitrix\Main\Application;


$conn = Application::getConnection();
$entity = \Root\Www\Entity\ThanksTable::getEntity();
$table = $entity->getDBTableName();

if(!$conn->isTableExists($table)) {
    $entity->createDbTable();
}

use \Root\Www\Entity\ThanksTable;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Security\Random;

//$result = ThanksTable::add(array(
//    'USER' => Random::getInt(1, 3),
//    'DEPARTMENT' => Random::getInt(1, 3),
//    'THANKS_HI' => Random::getInt(1, 100),
//    'THANKS_HIM' => Random::getInt(1, 100),
//    'DATE' => DateTime::createFromTimestamp(Random::getInt(1346506620, 1637572749)),
//));


