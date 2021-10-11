<?php

/*
 * This file is part of ibrand/coterie-server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$router->post('wechat/notify', 'WechatPayNotifyController@notify');

$router->post('coterie/test', 'CoterieController@test');

$router->get('coterie/share', 'CoterieController@share');

$router->get('content/share', 'ContentController@share');

$router->get('coterie', 'CoterieController@show');

$router->get('coterie/recommend', 'CoterieController@getRecommend');

$router->get('coterie/search', 'CoterieController@search');

$router->get('content/info', 'ContentController@showInfo');

$router->group(config('ibrand.coterie.routeAuthAttributes'), function ($router) {
    /************************* 上传图片接口 **********************/
    $router->post('upload/image', 'UploadController@ImageUpload');

    /************************* 用户 **********************/
    $router->get('me', 'UserController@me')->name('api.me');

    $router->post('users/update/info', 'UserController@updateInfo');

    /************************* 圈子接口 **********************/

    $router->post('coterie/store', 'CoterieController@store');

    $router->get('coterie/edit', 'CoterieController@edit');

    $router->post('coterie/update', 'CoterieController@update');

    $router->get('coterie/user', 'CoterieController@userCoterie');

    $router->post('coterie/delete', 'CoterieController@delete');

    $router->post('coterie/share', 'CoterieController@getCoterieImage');

    /************************* 圈子会员接口 **********************/

    $router->get('member', 'MemberController@index');

    $router->get('member/invite/code', 'MemberController@getInviteCode');

    $router->post('member/store/code', 'MemberController@storeBycode');

    $router->post('member/store', 'MemberController@store');

    $router->post('member/user', 'MemberController@updateUserType');

    $router->post('member/forbidden', 'MemberController@forbidden');

    $router->post('member/delete', 'MemberController@delete');

    $router->post('member/quit', 'MemberController@quit');

    /************************* 圈子内容接口 **********************/

    $router->get('coterie/content', 'ContentController@index');

    $router->post('content/query/link', 'ContentController@queryLink');

    $router->post('content/store', 'ContentController@store');

    $router->get('content/edit', 'ContentController@edit');

    $router->post('content/update', 'ContentController@update');

    $router->get('content/stick', 'ContentController@getContentStickAt');

    $router->post('content/stick', 'ContentController@setContentStickAt');

    $router->post('content/recommended', 'ContentController@setContentRecommendedAt');

    $router->get('content/tags', 'ContentController@getHostTags');

    $router->get('content', 'ContentController@show');

    $router->post('content/delete', 'ContentController@delete');

    $router->post('content/share', 'ContentController@getContentImage');

    /************************* 圈子提问接口 **********************/

    $router->get('question', 'QuestionController@index');

    $router->post('question/store', 'QuestionController@storeQuestion');

    /*************************评论接口 **********************/

    $router->get('comment', 'CommentController@index');

    $router->post('comment/store', 'CommentController@store');

    $router->get('comment/edit', 'CommentController@edit');

    $router->post('comment/update', 'CommentController@update');

    $router->post('comment/delete', 'CommentController@delete');

    /*************************评论点赞 **********************/

    $router->post('comment/praise/store', 'CommentController@praiseStore');

    $router->post('comment/praise/delete', 'CommentController@praiseDelete');

    /*************************内容点赞接口 **********************/

    $router->post('content/praise/store', 'PraiseController@store');

    $router->post('content/praise/delete', 'PraiseController@delete');

    /*************************回复评论接口 **********************/

    $router->post('reply/store', 'ReplyController@store');

    $router->get('reply/edit', 'ReplyController@edit');

    $router->post('reply/update', 'ReplyController@update');

    $router->post('reply/delete', 'ReplyController@delete');

    $router->post('reply/praise/store', 'ReplyController@praiseStore');

    /************************* 订单接口 **********************/

    $router->post('order/store', 'OrderController@store');

    /************************* 支付接口 **********************/

    $router->post('coterie/payment', 'PaymentController@coterie');

    $router->post('coterie/payment/success', 'PaymentController@coterieSuccess');

    /************************* 消息通知接口 **********************/

    $router->get('notification/test', 'NotificationController@test');

    $router->get('notification', 'NotificationController@index');

    $router->get('notification/praise', 'NotificationController@praiseList');

    $router->post('notification/praise/mark/read', 'NotificationController@markPraiseAllRead');

    $router->get('notification/praise/unread/count', 'NotificationController@unreadPraiseCount');
});

//});
