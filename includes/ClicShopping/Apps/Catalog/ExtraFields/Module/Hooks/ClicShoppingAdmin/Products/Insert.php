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

  class Insert implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;

    public function __construct()
    {
      if (!Registry::exists('ExtraFields')) {
        Registry::set('ExtraFields', new ExtraFieldsApp());
      }

      $this->app = Registry::get('ExtraFields');
    }

    private function save()
    {
      if (isset($_GET['Insert'])) {
        $Qproducts = $this->app->db->prepare('select products_id
                                              from :table_products
                                              order by products_id desc
                                               limit 1
                                              ');
        $Qproducts->execute();

        $id = $Qproducts->valueInt('products_id');

        /*
                $QextraFields = $this->app->db->prepare('select *
                                                        from :table_products_to_products_extra_fields
                                                        where products_id = :products_id
                                                        ');
                $QextraFields->bindInt(':products_id', $id);
                $QextraFields->execute();

                while ($QextraFields->fetch()) {
                  $extra_product_entry[$QextraFields->valueInt('products_extra_fields_id')] = $QextraFields->valueInt('products_extra_fields_value');
                }
        */
      if (isset($_POST['extra_field'])) { // Check to see if there are any need to update extra fields.
        foreach ($_POST['extra_field'] as $key => $val) {

          if ($val == 'NO_DISPLAY_CHECKBOX') {
            $val = '';
          }

            if (!empty($val)) {
              $this->app->db->save('products_to_products_extra_fields', [
                  'products_id' => (int)$id,
                  'products_extra_fields_id' => (int)$key,
                  'products_extra_fields_value' => $val
                ]
              );
            }
          }
        } // end foreach
      }
    }

    public function execute()
    {

      if (!defined('CLICSHOPPING_APP_EXTRA_FIELDS_EF_STATUS') || CLICSHOPPING_APP_EXTRA_FIELDS_EF_STATUS == 'False') {
        return false;
      }

      $this->save();
    }
  }