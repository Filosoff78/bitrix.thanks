<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\PageNavigation;
use \Root\Www\Entity\ThanksTable;

class FilsoffThanksComponent extends CBitrixComponent implements \Bitrix\Main\Engine\Contract\Controllerable {

    const GRID_ID = 'filosoff_grid5';
    const FILTER_ID = 'filosoff_grid5';
    public function changeResultAction()
    {
        $grid_options = new GridOptions(self::GRID_ID);
        foreach ($this->arResult['GRID']['COLUMNS'] as &$col)
        {
            if($col['id'] === 'THANKS_HI')
            {
                $col['default'] = false;
            }
        }
        return $grid_options;
    }
    public function executeComponent()
    {
        $this->arResult['FILTER'] = [
            'GRID_ID' => self::GRID_ID,
            'FILTER_ID' => self::FILTER_ID,
            'ENABLE_LABEL' => true,
            'ENABLE_LIVE_SEARCH' => true,
            'FILTER' => [
                ['id' => 'DATE', 'name' => 'Дата', 'type' => 'date'],
                ['id' => 'DEPARTMENT', 'name' => 'Департмаент', 'type' => 'dest_selector', 'params' =>
                    [
                        'enableCrm' => 'N',
                        'enableUsers' => 'N',
                        'enableDepartments' => 'Y',
                    ],
                ],
            ],
        ];
        $columns[] = ['id' => 'USER', 'name' => 'Пользователь', 'sort' => 'USER', 'default' => true];
        $columns[] = ['id' => 'THANKS_HI', 'name' => 'Количество благодарностей (он)', 'sort' => 'THANKS_HI', 'default' => true];
        $columns[] = ['id' => 'THANKS_HIM', 'name' => 'Количество благодарностей (ему)', 'sort' => 'THANKS_HIM', 'default' => true];
        $columns[] = ['id' => 'DATE', 'name' => 'Дата', 'sort' => 'DATE', 'default' => true];
        $columns[] = ['id' => 'DEPARTMENT', 'name' => 'Департмаент', 'sort' => 'DEPARTMENT', 'default' => true];

        $grid_options = new GridOptions(self::GRID_ID);
        $nav_params = $grid_options->GetNavParams();

        $sort = $grid_options->GetSorting(['sort' => ['THANKS_HI' => 'ASC'], 'vars' => ['by' => 'by', 'order' => 'order']]);

        $nav = new PageNavigation(self::GRID_ID);
        $nav->allowAllRecords(true)
            ->setPageSize($nav_params['nPageSize'])
            ->initFromUri();

        $filterOption = new Bitrix\Main\UI\Filter\Options(self::GRID_ID);
        $filterData = $filterOption->getFilter([]);
        $filter = [];
        $dateFilter = ['01.01.1970 03:00:00','01.01.2970 03:00:00'];

        foreach ($filterData as $k => $v)
        {
            if($k == 'DATE_from' || $k == 'DATE_to')
            {
                if (preg_match('#(_from|_to)#', $k, $logics))
                {
                    $logics = $logics[1];
                    if ($logics=="_from" && !empty($v)) $dateFilter[0] = $v;
                    elseif($logics=="_to" && !empty($v)) $dateFilter[1] = $v;
                }
            }
            if($k == 'DEPARTMENT_label') {
                $filter['=DEP.NAME'] = ['=DEP.NAME' => $v];
            }
        }
        $filter['><DATE'] = [$dateFilter[0],$dateFilter[1]];

        $res = ThanksTable::getList([
            'filter' => $filter,
            'select' => [
                'USER', 'THANKS_HI', 'THANKS_HIM', 'DATE', 'DEPARTMENT',
                'NAME' => 'USER_NAME.NAME',
                'LAST_NAME' => 'USER_NAME.LAST_NAME',
                'DEP_NAME' => 'DEP.NAME'
            ],
            'offset'      => $nav->getOffset(),
            'limit'       => $nav->getLimit(),
            'order'       => $sort['sort'],
            'count_total' => true,
        ]);

        $nav->setRecordCount($res->getCount());

        foreach ($res->fetchAll() as $row)
        {
            $list[] = [
                'data' => [
                    "USER" => $row['NAME'] . ' ' . $row['LAST_NAME'],
                    "THANKS_HI" => $row['THANKS_HI'],
                    "THANKS_HIM" => $row['THANKS_HIM'],
                    "DATE" => $row['DATE'],
                    "DEPARTMENT" => $row['DEP_NAME'],
                ],
            ];
        }

        $this->arResult['GRID'] = [
            'GRID_ID' => self::GRID_ID,
            'COLUMNS' => $columns,
            'ROWS' => $list,
            'SHOW_ROW_CHECKBOXES' => false,
            'NAV_OBJECT' => $nav,
            'AJAX_MODE' => 'Y',
            'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
            'PAGE_SIZES' =>  [
                ['NAME' => '20', 'VALUE' => '20'],
                ['NAME' => '50', 'VALUE' => '50'],
                ['NAME' => '100', 'VALUE' => '100']
            ],
            'AJAX_OPTION_JUMP'          => 'N',
            'SHOW_CHECK_ALL_CHECKBOXES' => false,
            'SHOW_ROW_ACTIONS_MENU'     => true,
            'SHOW_GRID_SETTINGS_MENU'   => true,
            'SHOW_NAVIGATION_PANEL'     => true,
            'SHOW_PAGINATION'           => true,
            'SHOW_SELECTED_COUNTER'     => true,
            'SHOW_TOTAL_COUNTER'        => true,
            'SHOW_PAGESIZE'             => true,
            'SHOW_ACTION_PANEL'         => true,
            'ALLOW_COLUMNS_SORT'        => true,
            'ALLOW_COLUMNS_RESIZE'      => true,
            'ALLOW_HORIZONTAL_SCROLL'   => true,
            'ALLOW_SORT'                => true,
            'ALLOW_PIN_HEADER'          => true,
            'AJAX_OPTION_HISTORY'       => 'N'
        ];
        $this->includeComponentTemplate();
    }

    public function configureActions()
    {

    }
}