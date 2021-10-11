﻿<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, shrink-to-fit=no">
    <title>首页</title>
    <style>
        body,
        html {
            padding: 0;
            margin: 0;
            width: 100%;
            height:400px;
        }
        #knowledge {
            /* height: 100%;
            overflow: auto; */
        }
        #knowledge .share-cirle {
            background-color: #FFFFFF;
            padding-bottom: 15px;
        }
        #knowledge .block {
            padding: 10px 15px;
            background: #ffffff;
            border-radius: 4px;
        }
        #knowledge .block.knowladge-info {
            position: relative;
            top: -75px;
            margin-bottom: -75px;
        }
        #knowledge .block.knowladge-info .knowladge-avatar {
            position: absolute;
            top: -35px;
            width: 70px;
            height: 70px;
            border: 2px solid #ffffff;
            border-radius: 100%;
            box-shadow: 0px 4px 8px 1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        #knowledge .block.knowladge-info .knowladge-avatar img {
            width: 100%;
            height: 100%;
        }
        #knowledge .block.knowladge-info .see-more {
            text-align: right;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-align-content: center;
            align-content: center;
            justify-content: flex-end;
            -webkit-justify-content: flex-end;
            color: #4A4A4A;
        }
        #knowledge .block.knowladge-info .see-more i {
            margin-top: 1px;
            margin-right: 5px;
            font-size: 13px;
            color: #4A4A4A;
        }
        #knowledge .block.knowladge-info .title {
            padding-top: 20px;
            font-size: 16px;
            color: #202020;
            font-weight: bold;
            line-height: 22px;
        }
        #knowledge .block.knowladge-info .guest-box {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-align-content: center;
            align-content: center;
            margin-top: 10px;
        }
        #knowledge .block.knowladge-info .guest-box .left {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-align-content: center;
            align-content: center;
            webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
        #knowledge .block.knowladge-info .guest-box .left .avatar-box {
            position: relative;
            margin-right: 7px;
        }
        #knowledge .block.knowladge-info .guest-box .left .avatar-box .item {
            position: relative;
            display: inline-block;
            vertical-align: middle;
            border-radius: 100%;
            width: 25px;
            height: 25px;
        }
        #knowledge .block.knowladge-info .guest-box .left .text {
            font-size: 12px;
            color: #D7D7D7;
        }
        #knowledge .block.knowladge-info .guest-box .right {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-align-content: center;
            align-content: center;
            color: #004E9D;
        }
        #knowledge .block.knowladge-info .guest-box .right i {
            margin-right: 5px;
        }
        #knowledge .block.topping {
            padding: 0;
            margin-top: 10px;
        }
        #knowledge .block.topping .title {
            font-size: 12px;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-align-content: center;
            align-content: center;
            padding: 12px 15px;
        }
        #knowledge .block.topping .title .text {
            flex: 1;
            color: #909090;
            webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
        #knowledge .block.topping .title .filter {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-align-content: center;
            align-content: center;
            color: #4A4A4A;
        }
        #knowledge .block.topping .title .filter i {
            font-size: 12px;
            margin-right: 5px;
        }
        #knowledge .block.topping .topping-item {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-align-content: center;
            align-content: center;
            padding: 12px 15px;
        }
        #knowledge .block.topping .topping-item .tag {
            padding: 1px 5px;
            color: #004E9D;
            border: 1px solid #004E9D;
            border-radius: 2px;
        }
        #knowledge .block.topping .topping-item .text {
            color: #909090;
            padding-left: 10px;

            webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
        #knowledge .block.topping .topping-item i {
            font-size: 12px;
            color: #d6d6d6;
        }
        #knowledge .block.knowladge-item .theme-item .avatar-box {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-align-content: center;
            align-content: center;
            padding: 10px 0;
        }
        #knowledge .block.knowladge-item .theme-item .avatar-box .img {
            width: 36px;
            height: 36px;
            border-radius: 100%;
            overflow: hidden;
        }
        #knowledge .block.knowladge-item .theme-item .avatar-box .img img {
            width: 100%;
            height: 100%;
        }
        #knowledge .block.knowladge-item .theme-item .avatar-box .info {
            overflow: hidden;

            margin-left: 15px;
            webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
        #knowledge .block.knowladge-item .theme-item .avatar-box .info .name {
            font-weight: bold;
            font-size: 14px;
        }
        #knowledge .block.knowladge-item .theme-item .avatar-box .info .name .topic {
            padding-left: 4px;
            display: inline-block;
            color: #4a4a4a;
        }
        #knowledge .block.knowladge-item .theme-item .avatar-box .info .time {
            font-size: 11px;
            color: #909090;
        }
        #knowledge .block.knowladge-item .theme-item .avatar-box .option {
            color: #4A4A4A;
            font-size: 25px;
        }
        #knowledge .block.knowladge-item .theme-item .img{
            height: 365px;
        }
        #knowledge .block.knowladge-item .theme-item .img .item__list {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            flex-wrap: wrap;
            -webkit-flex-wrap: wrap;
        }
        #knowledge .block.knowladge-item .theme-item .img .item__list ::after {
            content: "";
            display: block;
            clear: both;
            height: 0;
            overflow: hidden;
            visibility: hidden;
        }
        /* #knowledge .block.knowladge-item .theme-item .img .item__list .img {
             width: 100%;
         } */
        /* #knowledge .block.knowladge-item .theme-item .img .item__list .img img {
             width: 100%;
         } */
        #knowledge .block.knowladge-item .theme-item .img .item__list .list__li {
            width: 31.33%;
            padding: 1%;
        }
        #knowledge .block.knowladge-item .theme-item .img .item__list .list__li .more {
            margin: 0 -1%;
        }
        #knowledge .block.knowladge-item .theme-item .img .item__list .list__li div {
            position: relative;
            width: 100%;
            height: 0;
            overflow: hidden;
            margin: 0;
            padding-bottom: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        #knowledge .block.knowladge-item .theme-item .img .item__list .list__li div span {
            display: block;
            position: absolute;
            width: 100%;
            top: 0;
            bottom: 0;
        }
        #knowledge .block.knowladge-item .theme-item .text {
            color: #202020;
            line-height: 24px;
            height: 48px;
            overflow : hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        #knowledge .block.knowladge-item .theme-item .url {
            margin-top: 10px;
            width: 100%;
        }
        #knowledge .block.knowladge-item .theme-item .url .edit-link {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -webkit-justify-content: space-between;
            -ms-flex-pack: justify;
            justify-content: space-between;
            background-color: #F3F3F3;
            padding: 10px;
            position: relative;
        }
        #knowledge .block.knowladge-item .theme-item .url .edit-link .link-left {
            webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
        #knowledge .block.knowladge-item .theme-item .url .edit-link .link-left .title {
            color: #4A4A4A;
            font-size: 12px;
            line-height: 17px;
            padding-bottom: 16px;
        }
        #knowledge .block.knowladge-item .theme-item .url .edit-link .link-left .txt {
            color: #909090;
            font-size: 12px;
            line-height: 17px;
        }
        #knowledge .block.knowladge-item .theme-item .url .edit-link .link-right {
            width: 50px;
            height: 50px;
            background-color: #004E9D;
            line-height: 50px;
            text-align: center;
        }
        #knowledge .block.knowladge-item .theme-item .url .edit-link .link-right img {
            vertical-align: middle;
            display: inline-block;
        }
        #knowledge .block.knowladge-item .theme-item .reply-box .ask {
            background: #F8F8F8;
            border-radius: 2px;
            color: #4A4A4A;
            margin-bottom: 10px;
        }
        #knowledge .block.knowladge-item .theme-item .reply-box .ask .text {
            padding: 5px 10px;
        }
        #knowledge .block.knowladge-item .theme-item .tag-box {
            margin-top: 20px;
            color: #004E9D;
        }
        #knowledge .block.knowladge-item .theme-item .tag-box span {
            margin-right: 10px;
        }
        #knowledge .block.knowladge-item .theme-item .operating-box {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            margin-top: 15px;
        }
        #knowledge .block.knowladge-item .theme-item .operating-box .item {
            font-size: 13px;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            color: #4a4a4a;
            margin-right: 25px;
        }
        #knowledge .block.knowladge-item .theme-item .operating-box .item i {
            margin-right: 5px;
        }
        #knowledge .block.knowladge-item .theme-item .operating-box .item.active {
            color: #004E9D;
        }
        #knowledge .block.knowladge-item .theme-item .awesome-box {
            margin-top: 10px;
            color: #004E9D;
        }
        #knowledge .block.knowladge-item .theme-item .awesome-box .iconfont {
            margin-right: 5px;
        }
        #knowledge .block.knowladge-item .theme-item .comment-box {
            margin-top: 10px;
        }
        #knowledge .block.knowladge-item .theme-item .comment-box .comment-item {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            margin-bottom: 5px;
        }
        #knowledge .block.knowladge-item .theme-item .comment-box .comment-item .name {
            max-width: 85px;
            color: #004E9D;
        }
        #knowledge .block.knowladge-item .theme-item .comment-box .comment-item .text {
            webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
            color: #4A4A4A;
        }
        #knowledge .code-box {
            margin: 0px 15px 0px 15px;
            background: #f3f3f3;
            border-radius: 4px;
            padding: 12px;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
        }
        #knowledge .code-box .left-item {

            webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
        #knowledge .code-box .left-item .top-info {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
        }
        #knowledge .code-box .left-item .top-info .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            margin-right: 10px;
        }
        #knowledge .code-box .left-item .top-info .avatar img {
            width: 100%;
            height: 100%;
            vertical-align: middle;
            border-radius: 50%;
        }
        #knowledge .code-box .left-item .top-info .text {
            webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
        #knowledge .code-box .left-item .top-info .text .nick-name {
            color: #202020;
            line-height: 18px;
            font-size: 13px;
            padding-bottom: 5px;
            font-weight: bold;
        }
        #knowledge .code-box .left-item .top-info .text .name {
            color: #909090;
            line-height: 17px;
            font-size: 12px;
        }
        #knowledge .code-box .left-item .bottom-info {
            padding-top: 60px;
            color: #909090;
            line-height: 17px;
            font-size: 12px;
        }
        #knowledge .code-box .erweima {
            width: 132px;
            height: 132px;
        }
        #knowledge .code-box .erweima img {
            width: 100%;
            height: 100%;
        }


    </style>
</head>
<body>
<div id="knowledge">
    <div class="share-cirle">
        <div class="block knowladge-item">
            <div class="theme-item">
                <div class="avatar-box">
                    <div class="img">
                        <img src="{{isset($content->meta_info->user->avatar)?$content->meta_info->user->avatar:'http://img.alicdn.com/tps/TB1ld1GNFXXXXXLapXXXXXXXXXX-200-200.png'}}">
                    </div>
                    <div class="info">
                        <div class="name">
                            {{isset($content->meta_info->user->nick_name)?$content->meta_info->user->nick_name:''}}
                            <div class="topic">的话题</div>
                        </div>
                        <div class="time">
                            来自圈子：{{$content->coterie->name}}
                        </div>
                    </div>
                </div>


                @if($content->style_type=='default')
                    <div class="text">
                        分享一个 {{$content->description}}
                    </div>
                @endif


                <div class="img" style="height: auto;">
                    <div class="item__list">
                        <!--如果图片长度为1，显示一张-->
                        <!-- <div class="img" bindtap="preImage">
                             <img  src="https://wx.qlogo.cn/mmopen/vi_32/QvyPibAqLH5uEr7GNL6Lg9gvZxLYoARiawTicOVLySNYh7ABJYH2GPRLbX54F4jUHibag5QIrRbwKCS0ibYWmNUL8VA/132">
                         </div>-->

                        @if($content->style_type!=='question')

                            @if($content->img_list_info AND !$content->link_info)
                                <div class="img" style="width: 100%;">
                                    <div class="item__list more">

                                        @foreach($content->img_list_info as $item)

                                            <div class="list__li">
                                                <div style="background: url('{{$item}}'); background-position: center;background-repeat: no-repeat;background-size: cover;">
                                                    <span></span>
                                                </div>
                                            </div>

                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if($content->link_info || !$content->img_list_info)
                            <div style="height: 350px;">
                            </div>
                        @endif

                        @if($content->link_info)
                            <div class="url">
                                <div class="edit-link">
                                    <div class="link-left">
                                        <div class="title">{{$content->link_info['title']}}</div>
                                        <div class="txt">{{$content->link_info['link']}}</div>
                                    </div>
                                <!-- <div class="link-right">
										<img src="{{$content->link_info['img']}}" alt="">
									</div> -->
                                </div>
                            </div>
                        @endif

                        @if($content->question)
                            <div class="ask mx-1px-left">

                                <div class="text">
                                    {{$content->question->user->nick_name}}提问：{{$content->question->content}}。
                                </div>
                                <div class="img">
                                    <div class="item__list more">

                                        @if($content->question->img_list_info)

                                            @foreach($content->question->img_list_info as $item)

                                                <div class="list__li">
                                                    <div style="background: url('{{$item}}'); background-position: center;background-repeat: no-repeat;background-size: cover;">
                                                        <span></span>
                                                    </div>
                                                </div>

                                            @endforeach

                                        @endif


                                    </div>
                                </div>
                            </div>


                        @endif

                        <div class="reply-box">
                            @if($content->style_type=='question')
                                <div class="reply">
                                    {{$content->description}}
                                </div>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="code-box">
            <div class="left-item">
                <div class="top-info">
                    <div class="avatar">
                        <img src="{{isset($user['avatar'])?$user['avatar']:'http://img.alicdn.com/tps/TB1ld1GNFXXXXXLapXXXXXXXXXX-200-200.png'}}" alt="">
                    </div>
                    <div class="text">
                        <div class="nick-name">{{isset($user['nick_name'])?$user['nick_name']:''}}</div>
                        <div class="name">{{$content->coterie->name}}</div>
                    </div>

                </div>
                <div class="bottom-info">长按二维码识别，阅读原文</div>
            </div>
            <div class="erweima">
                <img src="{{request('mini_code')}}" alt="">
            </div>
        </div>
    </div>
</div>
</body>
</html>