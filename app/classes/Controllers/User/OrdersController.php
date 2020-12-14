<?php

namespace App\Controllers\User;

use App\Controllers\Base\UserController;
use App\Views\BasePage;
use App\Views\Tables\User\OrderTable;

class OrdersController extends UserController
{
    protected BasePage $page;

    public function __construct()
    {
        parent::__construct();
        $this->page = new BasePage([
            'title' => 'Orders',
        ]);
    }

    public function index(): ?string
    {
        $table = new OrderTable();
        $this->page->setContent($table->render());
        return $this->page->render();
    }
}