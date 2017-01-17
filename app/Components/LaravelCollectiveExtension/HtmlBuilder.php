<?php
namespace App\Components\LaravelCollectiveExtension;

use Collective\Html\HtmlBuilder as CollectiveHtmlBuilder;
use App\Http\Kernel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * HtmlBuilder
 */
class HtmlBuilder extends CollectiveHtmlBuilder
{

 /**
  * Factory instance
  *
  * @var \Illuminate\Routing\UrlGenerator
  */
  protected $view;

  /**
   * Create a new HTML builder instance.
   *
   * @param \Illuminate\Contracts\Routing\UrlGenerator $url
   * @param \Illuminate\Contracts\View\Factory         $view
   */
  public function __construct(UrlGenerator $url = null, Factory $view)
  {
      $this->url = $url;
      $this->view = $view;
  }

  public function simplePagination($actualPage, $totalPages)
  {
      $actualPage = (int) $actualPage;
      $totalPages = (int) $totalPages;

      if ($actualPage == 1) {
          $previousPage = 0;
          $nextPage = $actualPage + 1;
      } elseif ($actualPage > 1 && $actualPage < $totalPages) {
          $previousPage = $actualPage - 1;
          $nextPage = $actualPage + 1;
      } elseif ($actualPage == $totalPages) {
          $previousPage = $actualPage - 1;
          $nextPage = 0;
      } elseif ($actualPage > $totalPages || $actualPage == 0) {
          return redirect()
            ->back()
            ->with('alert', 'Error en el numero de pagina requerida');
      }

      $buttomBack = \Html::decode($this->LinkBuilder($previousPage, '<i class="fa fa-chevron-left"></i>', 'btn btn-default btn-sm'));
      $buttomNext = \Html::decode($this->LinkBuilder($nextPage, '<i class="fa fa-chevron-right"></i>', 'btn btn-default btn-sm'));

      return $buttomBack . '' . $buttomNext;
  }

  public function classes(array $classes)
  {
    $atributte = '';
    foreach ($classes as $name => $bool) {
      if (is_int($name)) {
        $name = $bool;
        $bool = true;
      }
      if ($bool) {
        $atributte .= $name . ' ';
      }
    }
    if ($atributte) {
      return ' class='. $atributte .'"';
    }
     return '';
  }

  public function LinkBuilder($page, $title = null, $class)
  {
      if ($page == 0) return $this->linkRoute(\Request::route()->getName(), $title, [], $attributes = ['class' => $class, 'type' => 'button', 'disabled' => 'disabled', ]);

      \Request::merge(['page' => $page, ]);
      return $this->linkRoute(\Request::route()->getName(), $title, \Request::all(), $attributes = ['class' => $class, 'type' => 'button', ]);
  }

}
