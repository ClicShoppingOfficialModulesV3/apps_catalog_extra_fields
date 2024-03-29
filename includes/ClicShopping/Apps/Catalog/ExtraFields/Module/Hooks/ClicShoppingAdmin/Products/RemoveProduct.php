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

  namespace ClicShopping\Apps\Catalog\ExtraFields\Module\Hooks\ClicShoppingAdmin\Products;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTML;

  use ClicShopping\Apps\Catalog\ExtraFields\ExtraFields as ExtraFieldsApp;

  class removeProduct implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;

    public function __construct()
    {
      if (!Registry::exists('ExtraFields')) {
        Registry::set('ExtraFields', new ExtraFieldsApp());
      }

      $this->app = Registry::get('ExtraFields');
    }

    private function removeProducts($id)
    {
      if (!empty($_POST['products_ExtraFields'])) {
        $this->app->db->delete('products_to_products_extra_fields', ['products_id' => (int)$id]);
      }
    }


    public function execute()
    {
      if (!defined('CLICSHOPPING_APP_EXTRA_FIELDS_EF_STATUS') || CLICSHOPPING_APP_EXTRA_FIELDS_EF_STATUS == 'False') {
        return false;
      }

      if (isset($_POST['remove_id'])) $pID = $_POST['remove_id'];
      if (isset($_POST['pID'])) $pID = $_POST['pID'];

      if (isset($pID)) {
        $id = HTML::sanitize($pID);
        $this->removeProducts($id);
      }
    }
  }