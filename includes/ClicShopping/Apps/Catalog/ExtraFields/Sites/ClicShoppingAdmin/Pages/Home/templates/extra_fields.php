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

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;


  use ClicShopping\Apps\Customers\Groups\Classes\ClicShoppingAdmin\GroupsB2BAdmin;

  $CLICSHOPPING_ExtraFields = Registry::get('ExtraFields');
  $CLICSHOPPING_Page = Registry::get('Site')->getPage();
  $CLICSHOPPING_Language = Registry::get('Language');

  $languages = $CLICSHOPPING_Language->getLanguages();

  // Put languages information into an array for drop-down boxes

  $customers_group = GroupsB2BAdmin::getAllGroups();
  $customers_group_name = '';

  foreach ($customers_group as $value) {
    $customers_group_name .= '<option value="' . $value['id'] . '">' . $value['text'] . '</option>';
  }

  // Put languages information into an array for drop-down boxes
  $languages = $CLICSHOPPING_Language->getLanguages();
  $values[0] = ['id' => '0',
    'text' => $CLICSHOPPING_ExtraFields->getDef('text_all_languages')
  ];

  for ($i = 0, $n = count($languages); $i < $n; $i++) {
    $values[$i + 1] = ['id' => $languages[$i]['id'],
      'text' => $languages[$i]['name']
    ];
  }

  // Select the  fields type
  if (MODE_B2B_B2C == 'true') {
    $field_type_array = array(array('id' => '0', 'text' => $CLICSHOPPING_ExtraFields->getDef('entry_text')),
      array('id' => '1', 'text' => $CLICSHOPPING_ExtraFields->getDef('entry_text_area')),
      array('id' => '2', 'text' => $CLICSHOPPING_ExtraFields->getDef('entry_text_checkbox')),
    );
  } else {
    $field_type_array = array(array('id' => '0', 'text' => $CLICSHOPPING_ExtraFields->getDef('entry_text')));
  }


?>
<div class="contentBody">
  <div class="row">
    <div class="col-md-12">
      <div class="card card-block headerCard">
        <div class="row">
          <span
            class="col-md-1 logoHeading"><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'categories/products_options.gif', $CLICSHOPPING_ExtraFields->getDef('heading_title'), '40', '40'); ?></span>
          <span
            class="col-md-5 pageHeading"><?php echo '&nbsp;' . $CLICSHOPPING_ExtraFields->getDef('heading_title'); ?></span>
          <span
            class="col-md-6 text-end"> <?php echo HTML::button($CLICSHOPPING_ExtraFields->getDef('button_configure'), null, $CLICSHOPPING_ExtraFields->link('Configure'), 'primary'); ?></span>
        </div>
      </div>
    </div>
  </div>
  <div class="separator"></div>

  <?php echo HTML::form('add_field', $CLICSHOPPING_ExtraFields->link('ExtraFields&Add')); ?>
  <div class="text-end">
    <span><?php echo HTML::button($CLICSHOPPING_ExtraFields->getDef('button_insert'), null, null, 'success', null, 'sm'); ?></span>
  </div>
  <div class="separator"></div>
  <table class="table table-sm table-hover table-striped">
    <thead>
    <tr class="dataTableHeadingRow">
      <th><?php echo $CLICSHOPPING_ExtraFields->getDef('table_heading_fields'); ?></th>
      <th><?php echo $CLICSHOPPING_ExtraFields->getDef('table_heading_fields_type'); ?></th>
      <?php
        // Permettre l'affichage des couleurs des groupes en mode B2B
        if (MODE_B2B_B2C == 'true') {
          ?>
          <th><?php echo $CLICSHOPPING_ExtraFields->getDef('table_heading_customers_group'); ?></th>
          <?php
        }
      ?>
      <th class="text-center"><?php echo $CLICSHOPPING_ExtraFields->getDef('table_heading_order'); ?></th>
      <th class="text-center"><?php echo $CLICSHOPPING_ExtraFields->getDef('table_heading_language'); ?></th>
    </tr>
    </thead>
    <tr>
      <td
        class="dataTableContent"><?php echo HTML::inputField('field[name]', null, 'size=30 required aria-required="true" id="field[name]"', false, 'text', true); ?></td>
      <td><?php echo HTML::selectMenu('field[products_extra_fields_type]', $field_type_array, '0'); ?></td>
      <?php
        // Permettre l'affichage des couleurs des groupes en mode B2B
        if (MODE_B2B_B2C == 'true') {
          ?>
          <td><?php echo HTML::selectMenu('field[customers_group_id]', $customers_group, '0'); ?></td>
          <?php
        }
      ?>
      <td
        class="dataTableContent text-center"><?php echo HTML::inputField('field[order]', null, 'size=5', false, 'text', true); ?></td>
      <td class="dataTableContent text-center"><?php echo HTML::selectMenu('field[language]', $values, '0'); ?></td>
    </tr>
  </table>
  </form>
  <?php
    if (MODE_B2B_B2C == 'true') {
      ?>
      <div class="alert alert-info">
        <div><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'icons/help.gif', $CLICSHOPPING_ExtraFields->getDef('title_help_options')) . ' ' . $CLICSHOPPING_ExtraFields->getDef('title_help_options') ?></div>
        <div class="separator"></div>
        <div><?php echo $CLICSHOPPING_ExtraFields->getDef('text_help_options'); ?></div>
      </div>
      <?php
    }
  ?>
  <div class="separator"></div>
  <?php echo HTML::form('extra_fields', $CLICSHOPPING_ExtraFields->link('ExtraFields&Update')); ?>

  <div class="text-end">
    <span> <?php echo HTML::button($CLICSHOPPING_ExtraFields->getDef('button_update'), null, null, 'success', null, 'sm'); ?></span>
    <span> <?php echo HTML::button($CLICSHOPPING_ExtraFields->getDef('button_delete'), null, null, 'danger', null, 'sm'); ?></span>
  </div>

  <div class="separator"></div>

  <div class="">
    <table border="0" width="100%" cellspacing="2" cellpadding="2">
      <tr>
        <td>
          <table class="table table-sm table-hover table-striped">
            <thead>
            <tr class="dataTableHeadingRow">
              <th
                class="text-center"><?php echo $CLICSHOPPING_ExtraFields->getDef('table_heading_delete_fields'); ?></th>
              <th><?php echo $CLICSHOPPING_ExtraFields->getDef('table_heading_id_fields'); ?></th>
              <th>
              <?php echo $CLICSHOPPING_ExtraFields->getDef('table_heading_fields'); ?></td>
              <th>
              <?php echo $CLICSHOPPING_ExtraFields->getDef('table_heading_fields_type'); ?></td>
              <?php
                // Permettre l'affichage des couleurs des groupes en mode B2B
                if (MODE_B2B_B2C == 'true') {
                  ?>
                  <th><?php echo $CLICSHOPPING_ExtraFields->getDef('table_heading_customers_group'); ?></th>
                  <?php
                }
              ?>
              <th
                class="text-center"><?php echo $CLICSHOPPING_ExtraFields->getDef('table_heading_status_display'); ?></th>
              <th class="text-center"><?php echo $CLICSHOPPING_ExtraFields->getDef('table_heading_order'); ?></th>
              <th class="text-center"><?php echo $CLICSHOPPING_ExtraFields->getDef('table_heading_language'); ?></th>
            </tr>
            <?php
              $QproductsExtraFields = $CLICSHOPPING_ExtraFields->db->prepare('select *
                                                                               from :table_products_extra_fields
                                                                               order by products_extra_fields_id,
                                                                                        products_extra_fields_order
                                                                              ');
              $QproductsExtraFields->execute();

              while ($extra_fields = $QproductsExtraFields->fetch()) {
                ?>
                <tr>
                  <td class="text-center">
                    <input type="checkbox" name="selected[]"
                           value="<?php echo $QproductsExtraFields->valueInt('products_extra_fields_id'); ?>"/>
                  </td>
                  <td class="dataTableContent"
                      width="80"><?php echo $QproductsExtraFields->valueInt('products_extra_fields_id'); ?>
                  <td
                    class="dataTableContent"><?php echo HTML::inputField('field[' . $QproductsExtraFields->valueInt('products_extra_fields_id') . '][name]', $extra_fields['products_extra_fields_name'], 'size=30 required aria-required="true" id="name"', false, 'text', true); ?></td>

                  <?php
                    if ($QproductsExtraFields->valueInt('products_extra_fields_type') == 0) {
                      $extra_fields_type = $CLICSHOPPING_ExtraFields->getDef('entry_text');
                    } elseif ($QproductsExtraFields->valueInt('products_extra_fields_type') == 1) {
                      $extra_fields_type = $CLICSHOPPING_ExtraFields->getDef('entry_text_area');
                    } else {
                      $extra_fields_type = $CLICSHOPPING_ExtraFields->getDef('entry_text_checkbox');
                    }
                  ?>
                  <td
                    class="dataTableContent"><?php echo $extra_fields_type . HTML::hiddenField('field[' . $QproductsExtraFields->valueInt('products_extra_fields_id') . '][products_extra_fields_type]', $QproductsExtraFields->value('products_extra_fields_type')); ?> </td>
                  <?php
                    // Permettre l'affichage des couleurs des groupes en mode B2B
                    if (MODE_B2B_B2C == 'true') {
                      ?>
                      <td
                        class="text-center dataTableContent"><?php echo HTML::selectMenu('field[' . $QproductsExtraFields->valueInt('products_extra_fields_id') . '][customers_group]', $customers_group, $QproductsExtraFields->valueInt('customers_group_id'), ''); ?></td>
                      <?php
                    }
                  ?>
                  <td class="text-center dataTableContent">
                    <?php
                      if ($QproductsExtraFields->valueInt('products_extra_fields_status') == 1) {
                        echo '<a href="' . $CLICSHOPPING_ExtraFields->link('ExtraFields&SetFlag&flag=0&id=' . $QproductsExtraFields->valueInt('products_extra_fields_id')) . '"><i class="fas fa-check fa-lg" aria-hidden="true"></i></a>';
                      } else {
                        echo '<a href="' . $CLICSHOPPING_ExtraFields->link('ExtraFields&SetFlag&flag=1&id=' . $QproductsExtraFields->valueInt('products_extra_fields_id')) . '"><i class="fas fa-times fa-lg" aria-hidden="true"></i></a>';
                      }
                    ?>
                  </td>
                  <td
                    class="text-center"><?php echo HTML::inputField('field[' . $QproductsExtraFields->valueInt('products_extra_fields_id') . '][order]', $QproductsExtraFields->valueInt('products_extra_fields_order'), 'size=5', false, 'text', true); ?></td>
                  <td
                    class="text-center"><?php echo HTML::selectMenu('field[' . $QproductsExtraFields->valueInt('products_extra_fields_id') . '][languages_id]', $values, $QproductsExtraFields->valueInt('languages_id'), ''); ?></td>
                </tr>
                <?php
              }
            ?>
          </table>
        </td>
        <!-- body_text_eof //-->
      </tr>
      </form>
    </table>
  </div>
</div>
