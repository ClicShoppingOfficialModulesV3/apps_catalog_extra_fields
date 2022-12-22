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


  class Update implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;

    public function __construct()
    {
      if (!Registry::exists('ExtraFields')) {
        Registry::set('ExtraFields', new ExtraFieldsApp());
      }

      $this->app = Registry::get('ExtraFields');
    }

    private function save($id)
    {
      if (isset($_GET['Update'])) {
        $QextraFields = $this->app->db->prepare('select *
                                                from :table_products_to_products_extra_fields
                                                where products_id = :products_id
                                                ');
        $QextraFields->bindInt(':products_id', $id);
        $QextraFields->execute();

        while ($QextraFields->fetch()) {
          $extra_product_entry[$QextraFields->valueInt('products_extra_fields_id')] = $QextraFields->valueInt('products_extra_fields_value');
        }

        if (isset($_POST['extra_field'])) { // Check to see if there are any need to update extra fields.



          foreach ($_POST['extra_field'] as $key => $val) {

            if ($val == 'NO_DISPLAY_CHECKBOX') {
              $val = '';
            }

            if (isset($extra_product_entry[$key])) { // an entry exists
              if (empty($val)) {
                $Qdelete = $this->app->db->prepare('delete
                                                  from :table_products_to_products_extra_fields
                                                  where products_id = :products_id
                                                  and products_extra_fields_id = :products_extra_fields_id
                                                ');
                $Qdelete->bindInt(':products_id', $id);
                $Qdelete->bindInt(':products_extra_fields_id', (int)$key);
                $Qdelete->execute();

              } else {
                $Qupdate = $this->app->db->prepare('update :table_products_to_products_extra_fields
                                                   set products_extra_fields_value = :products_extra_fields_value
                                                   where products_id = :products_id
                                                   and products_extra_fields_id = :products_extra_fields_id
                                                  ');
                $Qupdate->bindValue(':products_extra_fields_value', $val);
                $Qupdate->bindInt(':products_id', (int)$id);
                $Qupdate->bindInt(':products_extra_fields_id', (int)$key);
                $Qupdate->execute();
              }
            } else { // an entry does not exist

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
    }

    public function execute()
    {

      if (!defined('CLICSHOPPING_APP_EXTRA_FIELDS_EF_STATUS') || CLICSHOPPING_APP_EXTRA_FIELDS_EF_STATUS == 'False') {
        return false;
      }

      if (isset($_GET['pID'])) {
        $id = HTML::sanitize($_GET['pID']);
        $this->save($id);
      }
    }
  }