<?php

namespace App\Controllers\Admin;

use App\App;
use App\Controllers\Base\AdminController;
use App\Views\BasePage;
use App\Views\Forms\Admin\Order\OrderStatusForm;
use App\Views\Tables\Admin\OrdersTable;

/**
 * Class AdminOrders
 * TODO Make an API approach of this shit
 *
 * @package App\Controllers\Admin
 * @author  Dainius Vaičiulis   <denncath@gmail.com>
 */
class OrdersController extends AdminController
{
    protected BasePage $page;
    protected OrderStatusForm $form;

    public function __construct()
    {
        parent::__construct();
        $this->page = new BasePage([
            'title' => 'Orders'
        ]);
        $this->form = new OrderStatusForm();
    }

    public function index()
    {
        $rows = App::$db->getRowsWhere('orders');

        if ($this->form->validate()) {
            $clean_inputs = $this->form->values();

            foreach ($rows as $id => $row) {
                if ($clean_inputs['row_id'] == $id) {
                    $row['status'] = $clean_inputs['status'];
                    App::$db->updateRow('orders', $id, $row);
                }
            }
        }

        $table = new OrdersTable();
        $this->page->setContent($table->render());
        return $this->page->render();
    }
}