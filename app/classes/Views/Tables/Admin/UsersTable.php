<?php

namespace App\Views\Tables\Admin;

use App\App;
use App\Views\Forms\Admin\User\UserRoleForm;
use Core\Views\Table;

class UsersTable extends Table
{
    public function __construct()
    {
        $this->form = new UserRoleForm();
        $user = App::$session->getUser();

        $rows = App::$db->getRowsWhere('users');

        foreach ($rows as $id => &$row) {
            $row = [
                'id' => $id,
                'email' => $row['email'],
                'role' => $row['role']
            ];

            if ($row['email'] !== $user['email']) {
                $row['role_form'] = (new UserRoleForm($row['role'], $row['id']))
                    ->render();
            } else {
                $row['role_form'] = '-';
            }
        }

        parent::__construct([
            'headers' => [
                'ID',
                'Email',
                'Role',
                'Actions'
            ],
            'rows' => $rows
        ]);
    }
}
