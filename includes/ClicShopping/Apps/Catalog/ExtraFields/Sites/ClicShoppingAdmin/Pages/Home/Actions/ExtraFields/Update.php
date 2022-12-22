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

  class Update extends \ClicShopping\OM\PagesActionsAbstract
  {
    public function execute()
    {
      $CLICSHOPPING_ExtraFields = Registry::get('ExtraFields');

      if ($_POST['field']) {
        foreach ($_POST['field'] as $key => $val) {
          $sql_data_array = ['products_extra_fields_name' => $val['name'],
            'languages_id' => (int)$val['languages_id'],
            'products_extra_fields_order' => (int)$val['order'],
            'customers_group_id' => (int)$val['customers_group'],
            'products_extra_fields_type' => (int)$val['products_extra_fields_type']
          ];

          $CLICSHOPPING_ExtraFields->db->save('products_extra_fields', $sql_data_array, ['products_extra_fields_id' => (int)$key]);
        }
      }

      if (isset($_POST['selected'])) {
        foreach ($_POST['selected'] as $key) {
//delete
          $Qdelete = $CLICSHOPPING_ExtraFields->db->prepare('delete
                                          from :table_products_extra_fields
                                          where products_extra_fields_id = :products_extra_fields_id
                                        ');
          $Qdelete->bindInt(':products_extra_fields_id', (int)$key);
          $Qdelete->execute();

          $Qdelete = $CLICSHOPPING_ExtraFields->db->prepare('delete
                                          from :table_products_to_products_extra_fields
                                          where products_extra_fields_id = :products_extra_fields_id
                                        ');
          $Qdelete->bindInt(':products_extra_fields_id', (int)$key);
          $Qdelete->execute();
        }
      }

      $CLICSHOPPING_ExtraFields->redirect('ExtraFields');
    }
  }