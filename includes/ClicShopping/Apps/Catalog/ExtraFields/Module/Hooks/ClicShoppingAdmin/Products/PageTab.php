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

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;
  use ClicShopping\Sites\ClicShoppingAdmin\HTMLOverrideAdmin;

  use ClicShopping\Apps\Catalog\ExtraFields\ExtraFields as ExtraFieldsApp;


  class PageTab implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;

    public function __construct()
    {
      if (!Registry::exists('ExtraFields')) {
        Registry::set('ExtraFields', new ExtraFieldsApp());
      }

      $this->app = Registry::get('ExtraFields');
    }

    public function display()
    {
      if (!defined('CLICSHOPPING_APP_EXTRA_FIELDS_EF_STATUS') || CLICSHOPPING_APP_EXTRA_FIELDS_EF_STATUS == 'False') {
        return false;
      }

      global $pInfo;

      $CLICSHOPPING_Template = Registry::get('TemplateAdmin');
      $CLICSHOPPING_Language = Registry::get('Language');

      $languages = $CLICSHOPPING_Language->getLanguages();

      if (!defined('CLICSHOPPING_APP_EXTRA_FIELDS_EF_STATUS') || CLICSHOPPING_APP_EXTRA_FIELDS_EF_STATUS == 'False') {
        return false;
      }

      $this->app->loadDefinitions('Module/Hooks/ClicShoppingAdmin/Products/PageTab');

      if (isset($_GET['pID'])) {
        $id = HTML::sanitize($_GET['pID']);

        $QproductsExtraFields = $this->app->db->prepare('select *
                                                         from :table_products_to_products_extra_fields
                                                         where products_id = :products_id
                                                        ');
        $QproductsExtraFields->bindInt(':products_id', $id);
        $QproductsExtraFields->execute();
        $productsExtraFields = $QproductsExtraFields->fetchAll();

      } else {
        $productsExtraFields = null;
      }

      $extra_field = [];

      if (is_array($productsExtraFields)) {
        foreach ($productsExtraFields as $value) {
          $extra_field[$value['products_extra_fields_id']] = $value['products_extra_fields_value'];
        }
      }

      if (count($extra_field) > 0) {
        $extra_field_array = ['extra_field' => $extra_field];
      } else {
        $extra_field_array = ['extra_field' => ''];
      }

      $pInfo->ObjectInfo($extra_field_array);

 // ---------------------
// extra fields
// ----------------------
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $languages_array[$languages[$i]['id']] = $languages[$i];
      }

      $QextraFields = $this->app->db->prepare('select *
                                                from :table_products_extra_fields
                                                order by products_extra_fields_order
                                              ');
      $QextraFields->execute();

      $content = ' <div class="adminformTitle">';

      while ($QextraFields->fetch()) {
// Display language icon or blank space
        if ($QextraFields->valueInt('languages_id') == 0) {
          $fields_products = '&nbsp;';
        } else {
          $fields_products = $CLICSHOPPING_Language->getImage($languages_array[$QextraFields->valueInt('languages_id')]['image']);
        }
// Display customers_group icon or blank space
        if ($QextraFields->valueInt('customers_group_id') > 0 and $QextraFields->valueInt('customers_group_id') < 99) {
          $fields_icon_customers_group = HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'icons/group_client.gif', $this->app->getDef('icon_edit_customers_group'), 16, 16);
        } else {
          $fields_icon_customers_group = '&nbsp;';
        }

        if ($QextraFields->valueInt('products_extra_fields_status') == 1) {
          $fields_icon_status = '<i class="fas fa-check fa-lg" aria-hidden="true"></i>';
        } else {
          $fields_icon_status = '<i class="fas fa-times fa-lg" aria-hidden="true"></i>';
        }

        $products_extra_fields_id = $QextraFields->valueInt('products_extra_fields_id');

        $content .= '
                  <div class="separator"></div>
                  <div>
                    <span>' . $fields_products . '&nbsp;</span>
                    <span class="col-sm-2">' . $QextraFields->value('products_extra_fields_name') . ' : </span>
                  </div>
                 ';
        if ($QextraFields->valueInt('products_extra_fields_type') == 1) {

          $content .= '
                    <div>
                      <span class="col-sm-10">
                        <div style="visibility:visible; display:block;">
                        ' . HTMLOverrideAdmin::textAreaCkeditor('extra_field[' .  $products_extra_fields_id . ']', 'soft','750', '200', str_replace('& ', '&amp; ', trim($pInfo->extra_field[$products_extra_fields_id] ?? null))) . '</div>
                      </span>
                    </div>
                    ';
        } elseif ($QextraFields->valueInt('products_extra_fields_type') == 0) {

          $content .= '
                      <div>
                        <div class="col-md-6">' . HTML::inputField('extra_field[' .  $products_extra_fields_id . ']', $pInfo->extra_field[$products_extra_fields_id] ?? null) . '</div>
                      </div>
                    ';

        } else {
          $checkbox_type_array = array(array('id' => 'NO_DISPLAY_CHECKBOX', 'text' => $this->app->getDef('no_display_checkbox')),
            array('id' => 'DISPLAY_CHECKBOX', 'text' => $this->app->getDef('display_checkbox'))
          );

          if (isset($pInfo->extra_field[$products_extra_fields_id])) {
            $result = $pInfo->extra_field[$products_extra_fields_id];
          } else {
            $result = null;
          }

          $content .= '
                      <div>
                        <div class="col-sm-6">' . HTML::selectField('extra_field[' . $products_extra_fields_id . ']', $checkbox_type_array, $result ) . '</div>
                      </div>
                      ';
        }

        $content .= '
                    <span class="float-end">' . $fields_icon_status . '&nbsp; ' . $fields_icon_customers_group . '</span>
                    <div class="separator"></div>
                  ';
      }

      $content .= '</div>
                  <div class="clearfix"></div>
                  <div class="separator"></div>
                  <div class="alert alert-info">
                    <div>' . HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'icons/help.gif', $this->app->getDef('title_help_options')) . ' ' . $this->app->getDef('title_help_options') . '</div>
                    <div class="separator"></div>
                    <div class="row">
                      <span class="col-sm-12">
                       ' . $this->app->getDef('help_options') . '
                        <blockquote><i><a data-toggle="modal" data-target="#myModalWysiwyg2">' . $this->app->getDef('text_help_wysiwyg') . '</a></i></blockquote>
                       <div class="modal fade" id="myModalWysiwyg2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                         <div class="modal-dialog">
                           <div class="modal-content">
                             <div class="modal-header">
                               <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                               <h4 class="modal-title" id="myModalLabel">' . $this->app->getDef('text_help_wysiwyg') . '</h4>
                             </div>
                             <div class="separator"></div>
                             <div class="modal-body text-center">' . HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'wysiwyg.png') . '</div>
                           </div>
                         </div>
                       </div>
                      </span>
                    </div>
                  </div>
                  ';

      $tab_title = $this->app->getDef('tab_extra_fields');
      $title = $this->app->getDef('text_extra_fields');

      $output = <<<EOD
<!-- ######################## -->
<!--  Start ExtraFieldsApp  -->
<!-- ######################## -->
<div class="tab-pane" id="section_ExtraFieldsApp_content">
  <div class="mainTitle">
    <span class="col-md-2">{$title}</span>
  </div>
  {$content}
</div>
<script>
$('#section_ExtraFieldsApp_content').appendTo('#productsTabs .tab-content');
$('#productsTabs .nav-tabs').append('    <li class="nav-item"><a data-target="#section_ExtraFieldsApp_content" role="tab" data-toggle="tab" class="nav-link">{$tab_title}</a></li>');
</script>
<!-- ######################## -->
<!-- End ExtraFieldsApp  -->
<!-- ######################## -->
EOD;

      return $output;
    }
  }
