<?php
/**
 * This file is part of the sales
 *
 * (c) cherrybeal <mcxzyang@gmail.com>
 *
 * This source file is subject to the MIT license is bundled
 * with the source code in the file LICENSE
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HookController extends Controller
{
    public function webhook(Request $request)
    {
        $target = '/var/www/salary-admin.cunguangmed.com';
        // $cmd = "cd $target && git pull 2>&1";
        $cmd = 'git pull';

        // $result = shell_exec($cmd. ' >> /var/log/webhook_finance_api.log')
        $output = shell_exec($cmd);

        return $output;
    }
}
