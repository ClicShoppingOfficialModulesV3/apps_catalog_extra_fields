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


  namespace ClicShopping\Apps\Catalog\ExtraFields\Sites\ClicShoppingAdmin\Pages\Home\Actions\ExtraFields;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTML;

  class Add extends \ClicShopping\OM\PagesActionsAbstract
  {
    public function execute()
    {
      $CLICSHOPPING_ExtraFields = Registry::get('ExtraFields');

      $sql_data_array = ['products_extra_fields_name' => HTML::sanitize($_POST['field']['name']),
        'languages_id' => (int)$_POST['field']['language'],
        'products_extra_fields_order' => (int)$_POST['field']['order'],
        'customers_group_id' => (int)$_POST['field']['customers_group_id'],
        'products_extra_fields_type' => (int)$_POST['field']['products_extra_fields_type']
      ];

      $CLICSHOPPING_ExtraFields->db->save('products_extra_fields', $sql_data_array);


      $CLICSHOPPING_ExtraFields->redirect('ExtraFields');
    }
  }