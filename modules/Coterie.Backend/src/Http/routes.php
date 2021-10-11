<?php

/*
 * This file is part of ibrand/coterie-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$router->group(['prefix' => 'admin/coterie'], function () use ($router) {
    $router->get('/', 'DashBoardController@index')->name('admin.account.coterie.index');

    $router->group(['prefix' => 'mini'], function () use ($router) {
        $router->group(['prefix' => 'version'], function () use ($router) {
            $router->get('/', 'MiniProgramController@version')->name('admin.account.coterie.mini.version');

            //$router->get('/send/log', 'MiniProgramController@sendLog')->name('admin.account.coterie.mini.version.send.log');

            $router->get('/model', 'MiniProgramController@Model')->name('admin.account.coterie.mini.version.model');

            $router->get('/getQrCode', 'MiniProgramController@getQrCode')->name('admin.account.coterie.mini.version.getQrCode');

            $router->get('/getUnlimit', 'MiniProgramController@getUnlimit')->name('admin.account.coterie.mini.version.getUnlimit');

            $router->post('/codeCommit', 'MiniProgramController@codeCommit')->name('admin.account.coterie.mini.version.codeCommit');

            $router->post('/submitAudit', 'MiniProgramController@submitAudit')->name('admin.account.coterie.mini.version.submitAudit');

            $router->post('/withdrawAudit', 'MiniProgramController@withdrawAudit')->name('admin.account.coterie.mini.version.withdrawAudit');

            $router->post('/reexaminationt', 'MiniProgramController@Reexamination')->name('admin.account.coterie.mini.version.Reexamination');

            $router->post('/release', 'MiniProgramController@release')->name('admin.account.coterie.mini.version.release');

            $router->post('/testerBind', 'MiniProgramController@testerBind')->name('admin.account.coterie.mini.version.testerBind');

            $router->post('/testerunBind', 'MiniProgramController@testerunBind')->name('admin.account.coterie.mini.version.testerunBind');
        });
    });

    $router->get('list', 'CoterieController@index')->name('admin.coterie.list');

    $router->get('show/{id}', 'CoterieController@show')->name('admin.coterie.show');
    $router->get('members/{id}', 'CoterieController@members')->name('admin.coterie.members');

    $router->post('switchRecommend', 'CoterieController@switchRecommend')->name('admin.coterie.switchRecommend');
    $router->post('delete', 'CoterieController@delete')->name('admin.coterie.delete');
    $router->post('restore', 'CoterieController@restore')->name('admin.coterie.restore');

    /*动态管理*/
    $router->group(['prefix' => 'content'], function () use ($router) {
        $router->get('list', 'ContentController@index')->name('admin.coterie.content.list');
        $router->get('show/{id}', 'ContentController@show')->name('admin.coterie.content.show');
        $router->post('switchRecommend', 'ContentController@switchRecommend')->name('admin.coterie.content.switchRecommend');
        $router->post('switchStick', 'ContentController@switchStick')->name('admin.coterie.content.switchStick');
        $router->post('delete', 'ContentController@delete')->name('admin.coterie.content.delete');
        $router->post('restore', 'ContentController@restore')->name('admin.coterie.content.restore');
        $router->post('audited', 'ContentController@audited')->name('admin.coterie.content.audited');
    });

    /*提问管理*/
    $router->group(['prefix' => 'question'], function () use ($router) {
        $router->get('list', 'QuestionController@index')->name('admin.coterie.question.list');
        $router->get('show/{id}', 'QuestionController@show')->name('admin.coterie.question.show');
        $router->post('delete', 'QuestionController@delete')->name('admin.coterie.question.delete');
        $router->post('restore', 'QuestionController@restore')->name('admin.coterie.question.restore');
    });

    /*设置*/
    $router->group(['prefix' => 'setting'], function () use ($router) {
        $router->get('paySetting', 'SettingController@paySetting')->name('admin.coterie.paySetting');
        $router->post('savePay', 'SettingController@savePay')->name('admin.coterie.savePay');
    });

    $router->get('users', 'UsersController@index')->name('admin.coterie.users.list');
});
