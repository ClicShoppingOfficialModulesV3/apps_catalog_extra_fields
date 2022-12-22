<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT
 *  @licence MIT - Portion of osCommerce 2.4
 *  @Info : https://www.clicshopping.org/forum/trademark/
 *
 */

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class pi_products_info_extra_fields {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_products_info_extra_fields_title');
      $this->description = CLICSHOPPING::getDef('module_products_info_extra_fields_description');

      if (defined('MODULE_PRODUCTS_INFO_EXTRA_FIELDS_STATUS')) {
        $this->sort_order = MODULE_PRODUCTS_INFO_EXTRA_FIELDS_SORT_ORDER;
        $this->enabled = (MODULE_PRODUCTS_INFO_EXTRA_FIELDS_STATUS == 'True');
      }
    }

    public function execute() {
      $CLICSHOPPING_Template = Registry::get('Template');
      $CLICSHOPPING_Customer = Registry::get('Customer');
      $CLICSHOPPING_Db = Registry::get('Db');
      $CLICSHOPPING_Language = Registry::get('Language');
      $CLICSHOPPING_ProductsCommon = Registry::get('ProductsCommon');

      $products_id = $CLICSHOPPING_ProductsCommon->getID();

      if (isset($products_id) && isset($_GET['Products'])) {
        $content_width = (int)MODULE_PRODUCTS_INFO_EXTRA_FIELDS_CONTENT_WIDTH;
        $text_position = MODULE_PRODUCTS_INFO_EXTRA_FIELDS_POSITION;

        if ($CLICSHOPPING_Customer->getCustomersGroupID() != 0) {

          $Qextra = $CLICSHOPPING_Db->prepare('select pef.products_extra_fields_status as status,
                                                       pef.products_extra_fields_name as name,
                                                       ptf.products_extra_fields_value as value,
                                                       pef.customers_group_id,
                                                       ptf.products_id
                                                from :table_products_extra_fields pef
                                                      left join :table_products_to_products_extra_fields ptf on ptf.products_extra_fields_id = pef.products_extra_fields_id
                                                where ptf.products_id = :products_id
                                                and ptf.products_extra_fields_value <> ""
                                                and (pef.languages_id = 0 or pef.languages_id = :languages_id)
                                                and (pef.customers_group_id = :customers_group_id or pef.customers_group_id = 99)
                                                order by products_extra_fields_order
                                             ');
          $Qextra->bindInt(':products_id', $products_id);
          $Qextra->bindInt(':languages_id', (int)$CLICSHOPPING_Language->getId());
          $Qextra->bindInt(':customers_group_id', (int)$CLICSHOPPING_Customer->getCustomersGroupID());
          $Qextra->execute();
        } else {
          $Qextra = $CLICSHOPPING_Db->prepare('select pef.products_extra_fields_status as status,
                                                       pef.products_extra_fields_name as name,
                                                       ptf.products_extra_fields_value as value,
                                                       pef.products_extra_fields_type,
                                                       pef.customers_group_id,
                                                       ptf.products_id
                                                from :table_products_extra_fields pef
                                                     left join :table_products_to_products_extra_fields ptf on ptf.products_extra_fields_id = pef.products_extra_fields_id
                                                where ptf.products_id = :products_id
                                                and ptf.products_extra_fields_value <> ""
                                                and (pef.languages_id = 0 or pef.languages_id = :languages_id)
                                                and (pef.customers_group_id = 0 or pef.customers_group_id = 99)
                                                order by products_extra_fields_order
                                             ');
          $Qextra->bindInt(':products_id', $products_id);

          $Qextra->bindInt(':languages_id', $CLICSHOPPING_Language->getId());
          $Qextra->execute();
        }

         $products_info_extra_field_title_content = '<!-- Start products_info_extra_field -->' . "\n";
         $products_info_extra_field_title_content .= '<div class="clearfix"></div>';
         $products_info_extra_field_title_content .= '<div class="' . $text_position . ' col-md-' . $content_width . '">';
         $products_info_extra_field_title_content .= '<div class="separator"></div>';

        $text_value = '';
// show only enabled extra field
        while ($Qextra->fetch()) {
          if ($Qextra->value('products_extra_fields_type') == 2) {
            $text_value = '<input type="checkbox" name="extra_field"  id="extra_field" checked="checked" disabled>';
          } else {
            $text_value =  $Qextra->value('value');
          }

          $products_info_extra_field_title_content .= '<div class="ExtrafieldRow">';
          $products_info_extra_field_title_content .= '<span class="col-md-2 ProductsInfoExtraFieldName">' . HTML::outputProtected($Qextra->value('name')) . '&nbsp;&nbsp;</span>';
          $products_info_extra_field_title_content .= '<span class="col-md-10 ProductsInfoExtraFieldValue">' . $text_value . '</span>' . "\n";
          $products_info_extra_field_title_content .= '</div>';
        }
        $products_info_extra_field_title_content .= '</div>';
        $products_info_extra_field_title_content .= '<!-- products_info_extra_field end -->' . "\n";

        $CLICSHOPPING_Template->addBlock($products_info_extra_field_title_content, $this->group);
      } // php_self
    } // public function execute

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULE_PRODUCTS_INFO_EXTRA_FIELDS_STATUS');
    }

    public function install() {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to enable this module ?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_EXTRA_FIELDS_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want to enable this module in your shop ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Please select the width of the display?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_EXTRA_FIELDS_CONTENT_WIDTH',
          'configuration_value' => '12',
          'configuration_description' => 'Please enter a number between 1 and 12',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_content_module_width_pull_down',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'A quel endroit souhaitez-vous afficher le code barre ?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_EXTRA_FIELDS_POSITION',
          'configuration_value' => 'float-none',
          'configuration_description' => 'Affiche à gauche ou à droite<br><br><i>(Valeur Left = Gauche <br>Valeur Right = Droite <br>Valeur None = Aucun)</i>',
          'configuration_group_id' => '6',
          'sort_order' => '2',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'float-end\', \'float-start\', \'float-none\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort order',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_EXTRA_FIELDS_SORT_ORDER',
          'configuration_value' => '105',
          'configuration_description' => 'Sort order of display. Lowest is displayed first. The sort order must be different on every module',
          'configuration_group_id' => '6',
          'sort_order' => '3',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );
    }

    public function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys() {
      return array (
        'MODULE_PRODUCTS_INFO_EXTRA_FIELDS_STATUS',
        'MODULE_PRODUCTS_INFO_EXTRA_FIELDS_CONTENT_WIDTH',
        'MODULE_PRODUCTS_INFO_EXTRA_FIELDS_POSITION',
        'MODULE_PRODUCTS_INFO_EXTRA_FIELDS_SORT_ORDER'
      );
    }
  }
