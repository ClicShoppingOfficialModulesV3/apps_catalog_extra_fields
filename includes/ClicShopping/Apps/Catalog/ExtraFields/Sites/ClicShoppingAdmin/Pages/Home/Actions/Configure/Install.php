<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  namespace ClicShopping\Apps\Catalog\ExtraFields\Sites\ClicShoppingAdmin\Pages\Home\Actions\Configure;

  use ClicShopping\OM\Registry;

  use ClicShopping\OM\Cache;

  class Install extends \ClicShopping\OM\PagesActionsAbstract
  {

    public function execute()
    {

      $CLICSHOPPING_MessageStack = Registry::get('MessageStack');
      $CLICSHOPPING_ExtraFields = Registry::get('ExtraFields');

      $current_module = $this->page->data['current_module'];

      $CLICSHOPPING_ExtraFields->loadDefinitions('Sites/ClicShoppingAdmin/install');

      $m = Registry::get('ExtraFieldsAdminConfig' . $current_module);
      $m->install();

      static::installDbMenuAdministration();
      static::installProductsExtraFieldsDb();

      $CLICSHOPPING_MessageStack->add($CLICSHOPPING_ExtraFields->getDef('alert_module_install_success'), 'success');

      $CLICSHOPPING_ExtraFields->redirect('Configure&module=' . $current_module);
    }

    private static function installDbMenuAdministration()
    {
      $CLICSHOPPING_Db = Registry::get('Db');
      $CLICSHOPPING_ExtraFields = Registry::get('ExtraFields');
      $CLICSHOPPING_Language = Registry::get('Language');

      $Qcheck = $CLICSHOPPING_Db->get('administrator_menu', 'app_code', ['app_code' => 'app_catalog_extra_fields']);

      if ($Qcheck->fetch() === false) {

        $sql_data_array = ['sort_order' => 4,
          'link' => 'index.php?A&Catalog\ExtraFields&ExtraFields',
          'image' => 'products_options.gif',
          'b2b_menu' => 0,
          'access' => 0,
          'app_code' => 'app_catalog_extra_fields'
        ];

        $insert_sql_data = ['parent_id' => 3];

        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

        $CLICSHOPPING_Db->save('administrator_menu', $sql_data_array);

        $id = $CLICSHOPPING_Db->lastInsertId();

        $languages = $CLICSHOPPING_Language->getLanguages();

        for ($i = 0, $n = count($languages); $i < $n; $i++) {

          $language_id = $languages[$i]['id'];

          $sql_data_array = ['label' => $CLICSHOPPING_ExtraFields->getDef('title_menu')];

          $insert_sql_data = ['id' => (int)$id,
            'language_id' => (int)$language_id
          ];

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          $CLICSHOPPING_Db->save('administrator_menu_description', $sql_data_array);

        }

        Cache::clear('menu-administrator');
      }
    }

    private function installProductsExtraFieldsDb()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      $Qcheck = $CLICSHOPPING_Db->query('show tables like ":table_products_extra_fields"');

      if ($Qcheck->fetch() === false) {
        $CLICSHOPPING_Db->installNewDb('products_extra_fields');


                $sql = <<<EOD
        CREATE TABLE :table_products_extra_fields (
          products_extra_fields_id int(11) NOT NULL,
          products_extra_fields_name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
          products_extra_fields_order int(3) NOT NULL DEFAULT '0',
          products_extra_fields_status tinyint(1) NOT NULL DEFAULT '1',
          languages_id int(11) NOT NULL DEFAULT '0',
          customers_group_id int(11) NOT NULL,
          products_extra_fields_type int(1) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        ALTER TABLE :table_products_extra_fields
          ADD PRIMARY KEY (products_extra_fields_id),
          ADD KEY idx_products_extra_fields_name (products_extra_fields_name);

        ALTER TABLE :table_products_extra_fields
          MODIFY products_extra_fields_id int(11) NOT NULL AUTO_INCREMENT;
        EOD;

                $CLICSHOPPING_Db->exec($sql);

      }

      $Qcheck = $CLICSHOPPING_Db->query('show tables like ":table_products_to_products_extra_fields"');

      if ($Qcheck->fetch() === false) {
        $CLICSHOPPING_Db->installNewDb('products_to_products_extra_fields');


                $sql = <<<EOD
        CREATE TABLE :table_products_to_products_extra_fields (
          products_id int(11) NOT NULL DEFAULT 0,
          products_extra_fields_id int(11) NOT NULL DEFAULT 0,
          products_extra_fields_value text COLLATE utf8mb4_unicode_ci
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        ALTER TABLE :table_products_to_products_extra_fields
          ADD PRIMARY KEY (products_id, products_extra_fields_id),
          ADD KEY products_id (products_extra_fields_id);
        EOD;
                $CLICSHOPPING_Db->exec($sql);

      }
    }
  }
