<?php
namespace Drupal\admin_tests\Twig;

use Drupal\admin_tests\Controller\AdminTestsController;

class TwigExtension extends \Twig_Extension {

    /**
     * {@inheritdoc}
     * This function must return the name of the extension. It must be unique.
     */
    public function getName() {
        return 'render_tests_relacionados';
    }

  /**
   * @return array
   */
  public function getFunctions()
  {
	return [
	  new \Twig_SimpleFunction('renderTestsRelacionados', [$this, 'renderTestsRelacionados'])
	];
  }

  /**
   * Provides function to programmatically rendering a menu
   *   The machine configuration id of the menu to render
   */
  public function renderTestsRelacionados($testId) {

      $controller = new AdminTestsController();
      $html = $controller->page_test_completado($testId);

	return array('#markup' => drupal_render($html));
  }


}