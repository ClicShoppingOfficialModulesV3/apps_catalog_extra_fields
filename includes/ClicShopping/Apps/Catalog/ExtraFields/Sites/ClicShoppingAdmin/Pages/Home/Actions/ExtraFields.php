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

  namespace ClicShopping\Apps\Catalog\ExtraFields\Sites\ClicShoppingAdmin\Pages\Home\Actions;

  use ClicShopping\OM\Registry;

  class ExtraFields extends \ClicShopping\OM\PagesActionsAbstract
  {
    public function execute()
    {
      $CLICSHOPPING_ExtraFields = Registry::get('ExtraFields');

      $this->page->setFile('extra_fields.php');
      $this->page->data['action'] = 'ExtraFields';

      $CLICSHOPPING_ExtraFields->loadDefinitions('Sites/ClicShoppingAdmin/ExtraFields');
    }
  }