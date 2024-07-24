<?php

class CategoryPreview extends Module
{
    public function __construct()
    {
        $this->name = 'categorypreview';
        $this->author = 'Adilis';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->displayName = $this->l('Category Preview Button');
        $this->description = $this->l('Add a preview button to the category page');

        parent::__construct();
    }

    public function install()
    {
        return parent::install() && $this->registerHook('displayBackOfficeHeader');
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        if (($this->context->controller->controller_name ?? '') == 'AdminCategories') {
            $id_category = Tools::getValue('id_category');
            $category = new Category($id_category, $this->context->language->id);
            if (Validate::isLoadedObject($category) && !$category->active) {
                $this->context->controller->addJS($this->_path . 'views/js/categorypreview.js');
                $adtoken = Tools::getAdminToken('AdminCategories' . (int) Tab::getIdFromClassName('AdminCategories') . (int) $this->context->employee->id);

                $previewButtonUrl = $this->context->link->getCategoryLink($category->id, $category->link_rewrite);
                $previewButtonUrl .= (strpos($previewButtonUrl, '?') === false ? '?' : '&') . 'adtoken=' . $adtoken;
                $previewButtonUrl .= '&id_employee=' . $this->context->employee->id;

                Media::addJsDef([
                    'previewButtonText' => $this->l('Preview'),
                    'previewButtonUrl' => $previewButtonUrl,
                ]);
            }
        }
    }
}
