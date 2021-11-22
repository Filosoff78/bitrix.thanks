<?php

namespace Root\Www\Entity;

use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;

Loader::includeModule('iblock');

class ThanksTable extends DataManager
{
    public static function getTableName()
    {
        return 'f_thanks';
    }
    public static function getMap()
    {
        return [
            (new Fields\IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete(),
            (new Fields\IntegerField('USER'))
                ->configureRequired(),
            (new Fields\IntegerField('DEPARTMENT'))
                ->configureRequired(),
            (new Fields\IntegerField('THANKS_HI'))
                ->configureRequired(),
            (new Fields\IntegerField('THANKS_HIM'))
                ->configureRequired(),
            (new Fields\DatetimeField('DATE'))
                ->configureRequired()
                ->configureDefaultValue(date('d.m.Y H:i:s')),
            (new Fields\Relations\Reference(
                'USER_NAME',
                UserTable::class,
                Join::on('this.USER', 'ref.ID')
            )),
            new Fields\Relations\Reference('DEP', SectionTable::class, Join::on('this.DEPARTMENT', 'ref.ID'))
        ];
    }
}