<?php

/*
 * This file is part of ibrand/coterie-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Backend\Observers;

use iBrand\Backend\Models\Admin;
use iBrand\Component\Account\Models\Account;
use iBrand\Component\Account\Models\AccountApplication;
use Illuminate\Support\Facades\Cookie;

class AdminCreateObserver
{
    public function saved(Admin $admin)
    {
        if (!$account = Account::where('mobile', $admin->mobile)->first()) {
            $account = Account::create([
                'mobile' => $admin->mobile,
                'password' => $admin->password,
            ]);
        }

        $uuid = Cookie::get('ibrand_log_uuid');

        if ($uuid and
            $application = AccountApplication::where('uuid', $uuid)->first() and
            !$account->applications->where('id', $application->id)->first()
        ) {
            $account->applications()->attach($application->id, ['type' => 'staff']);
        }
    }
}
