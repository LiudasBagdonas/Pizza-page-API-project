<?php

namespace App\Controllers\Common;

use App\Abstracts\Controller;
use App\App;
use App\Views\BasePage;
use App\Views\Forms\Admin\Pizza\PizzaCreateForm;
use App\Views\Forms\Admin\Pizza\PizzaUpdateForm;
use App\Views\Forms\Admin\PizzaDeleteForm;
use App\Views\Forms\Admin\OrderCreateForm;
use Core\View;
use App\Views\Content\HomeContent;
use Core\Views\Link;

class HomeController
{
    protected $page;

    /**
     * Controller constructor.
     *
     * We can write logic common for all
     * other methods
     *
     * For example, create $page object,
     * set it's header/navigation
     * or check if user has a proper role
     *
     * Goal is to prepare $page
     */
    public function __construct()
    {
        $this->page = new BasePage([
            'title' => 'Pizzas',
            'js' => ['/media/js/home.js']
        ]);
    }

    /**
     * Home Controller Index
     *
     * @return string|null
     * @throws \Exception
     */
    public function index(): ?string
    {
        $user = App::$session->getUser();

        if ($user) {
            if ($user['role'] == 'admin') {
                $forms = [
                    'create' => (new PizzaCreateForm())->render(),
                    'update' => (new PizzaUpdateForm())->render()
                ];
            }

            $heading = "Zdarova, {$user['user_name']}";
            $links = [
                'login' => (new Link([
                    'url' => App::$router::getUrl('logout'),
                    'text' => 'Logout'
                ]))->render()
            ];
        } else {
            $heading = 'Prisijunkite';
            $links = [
                'login' => (new Link([
                    'url' => App::$router::getUrl('login'),
                    'text' => 'Login'
                ]))->render()
            ];
        }

        $content = (new View([
            'title' => 'Welcome to our pizzaria',
            'heading' => $heading,
            'forms' => $forms ?? [],
            'links' => $links ?? []
        ]))->render(ROOT . '/app/templates/content/index.tpl.php');

        $this->page->setContent($content);

        return $this->page->render();
    }
}

