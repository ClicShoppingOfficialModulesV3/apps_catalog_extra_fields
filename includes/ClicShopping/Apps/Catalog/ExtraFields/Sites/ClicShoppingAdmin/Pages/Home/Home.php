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

  namespace ClicShopping\Apps\Catalog\ExtraFields\Sites\ClicShoppingAdmin\Pages\Home;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Catalog\ExtraFields\ExtraFields;

  class Home extends \ClicShopping\OM\PagesAbstract
  {
    public mixed $app;

    protected function init()
    {
      $CLICSHOPPING_ExtraFields = new ExtraFields();
      Registry::set('ExtraFields', $CLICSHOPPING_ExtraFields);

      $this->app = Registry::get('ExtraFields');

      $this->app->loadDefinitions('Sites/ClicShoppingAdmin/main');
    }
  }
