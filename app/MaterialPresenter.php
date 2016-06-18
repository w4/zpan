<?php
namespace App;

use Illuminate\Pagination\BootstrapFourPresenter;
use Illuminate\Support\HtmlString;

/**
 * Paginator using material design lite.
 *
 * @author Jordan Doyle <jordan@doyle.wf>
 */
class MaterialPresenter extends BootstrapFourPresenter
{
    /**
     * Convert the URL window into Material Design HTML.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function render()
    {
        if ($this->hasPages()) {
            return new HtmlString(sprintf(
                '<ul class="pagination">%s %s %s</ul>',
                $this->getPreviousButton('<i class="material-icons">chevron_left</i>'),
                $this->getLinks(),
                $this->getNextButton('<i class="material-icons">chevron_right</i>')
            ));
        }

        return '';
    }

    /**
     * Get HTML wrapper for an available page link.
     *
     * @param  string $url
     * @param  int $page
     * @param  string|null $rel
     * @return string
     */
    protected function getAvailablePageWrapper($url, $page, $rel = null)
    {
        $rel = is_null($rel) ? '' : ' rel="' . $rel . '"';

        return sprintf('<li><a class="no-decoration" href="%s"%s>%s</a></li>', htmlentities($url), $rel, $page);
    }

    /**
     * Get HTML wrapper for disabled text.
     *
     * @param  string $text
     * @return string
     */
    protected function getDisabledTextWrapper($text)
    {
        return '<li class="disabled"><span>' . $text . '</span></li>';
    }

    /**
     * Get HTML wrapper for active text.
     *
     * @param  string $text
     * @return string
     */
    protected function getActivePageWrapper($text)
    {
        return '<li class="active"><span>' . $text . '</span></li>';
    }
}
