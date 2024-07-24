<?php

class CategoryController extends CategoryControllerCore
{
    public function init()
    {
        $id_category = (int) Tools::getValue('id_category');
        $this->category = new Category(
            $id_category,
            $this->context->language->id
        );

        ProductListingFrontController::init();

        if (Tools::getValue('adtoken') == Tools::getAdminToken('AdminCategories' . (int) Tab::getIdFromClassName('AdminCategories') . (int) Tools::getValue('id_employee'))) {
            $this->category->active = 1;
        }

        if (!Validate::isLoadedObject($this->category) || !$this->category->active) {
            header('HTTP/1.1 404 Not Found');
            header('Status: 404 Not Found');
            $this->setTemplate('errors/404');
            $this->notFound = true;

            return;
        } elseif (!$this->category->checkAccess($this->context->customer->id)) {
            header('HTTP/1.1 403 Forbidden');
            header('Status: 403 Forbidden');
            $this->errors[] = $this->trans('You do not have access to this category.', [], 'Shop.Notifications.Error');
            $this->setTemplate('errors/forbidden');

            return;
        }

        $categoryVar = $this->getTemplateVarCategory();

        $filteredCategory = Hook::exec(
            'filterCategoryContent',
            ['object' => $categoryVar],
            $id_module = null,
            $array_return = false,
            $check_exceptions = true,
            $use_push = false,
            $id_shop = null,
            $chain = true
        );
        if (!empty($filteredCategory['object'])) {
            $categoryVar = $filteredCategory['object'];
        }

        $this->context->smarty->assign([
            'category' => $categoryVar,
            'subcategories' => $this->getTemplateVarSubCategories(),
        ]);
    }
}
