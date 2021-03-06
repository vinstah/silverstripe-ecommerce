<?php

namespace Sunnysideup\Ecommerce\Pages;

class ProductGroupSearchPageController extends ProductGroupController
{
    private static $allowed_actions = [
        'debug' => 'ADMIN',
        'filterforgroup' => true,
        'ProductSearchForm' => true,
        'searchresults' => true,
        'resetfilter' => true,
    ];

    /**
     * Returns child product groups for use in 'in this section'. For example
     * the vegetable Product Group may have listed here: Carrot, Cabbage, etc...
     */
    public function MenuChildGroups()
    {
        return;
    }

    /**
     * The link that Google et al. need to index.
     *
     * @return string
     */
    public function CanonicalLink()
    {
        $link = $this->Link();

        $this->extend('updateCanonicalLink', $link);

        return $link;
    }

    protected function init()
    {
        parent::init();

        $this->isSearchResults = true;
    }
}
