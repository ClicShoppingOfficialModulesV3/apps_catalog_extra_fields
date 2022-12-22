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

  class SetFlag extends \ClicShopping\OM\PagesActionsAbstract
  {
    public function execute()
    {
      $CLICSHOPPING_ExtraFields = Registry::get('ExtraFields');

      if (isset($_GET['flag']) && isset($_GET['id'])) {
        $sql_data_array = ['products_extra_fields_status' => HTML::sanitize($_GET['flag'])];

        $CLICSHOPPING_ExtraFields->db->save('products_extra_fields', $sql_data_array, ['products_extra_fields_id' => (int)$_GET['id']]);
      }

      $CLICSHOPPING_ExtraFields->redirect('ExtraFields');
    }
  }