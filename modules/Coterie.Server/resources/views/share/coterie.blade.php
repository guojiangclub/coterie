<!DOCTYPE html>
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
        body,html{
            padding: 0;
            margin: 0;
            width: 100%;
            height:100%
        }
        #knowl .header {
            background-color: #ffffff;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            padding: 10px 13px;
        }
        #knowl .header .avatar {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }
        #knowl .header .avatar img {
            vertical-align: middle;
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }
        #knowl .header .txt {
            color: #004E9D;
            font-size: 13px;
            line-height: 18px;
        }
        #knowl .content {
            background-color: #004E9D;
        }
        #knowl .content .bg-top {
            height: 46px;
            background-color: #004E9D;
        }
        #knowl .content .bg-bottom {
            height: 12px;
            background-color: #004E9D;
        }
        #knowl .content .detail-box {
            position: relative;
            background-color: #ffffff;
            border-radius: 4px;
            padding: 50px 15px 12px 15px;
            margin: 0px 12px 0px 12px;
            text-align: center;
        }
        #knowl .content .detail-box .avatar {
            position: absolute;
            top: -36px;
            left: 50%;
            margin-left: -36px;
            width: 72px;
            height: 72px;
            border: 2px solid #ffffff;
            box-shadow: 0px 4px 8px 1px rgba(0, 0, 0, 0.1);
            border-radius: 50%;
        }
        #knowl .content .detail-box .avatar img {
            width: 100%;
            height: 100%;
            vertical-align: middle;
            border-radius: 50%;
        }
        #knowl .content .detail-box .nick-name {
            color: #202020;
            font-size: 13px;
            line-height: 18px;
            font-weight: bold;
            padding-bottom: 5px;
        }
        #knowl .content .detail-box .circle-master {
            color: #909090;
            font-size: 12px;
        }
        #knowl .content .detail-box .describle {
            text-align: left;
            padding: 30px 0 25px 0;
            color: #909090;
            font-size: 13px;
            line-height: 20px;
        }
        #knowl .content .detail-box .describle .txt {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }
        #knowl .content .detail-box .list {
            padding: 18px 0 25px 0;
            border-bottom: 1px solid #E6E6E6;
            border-top: 1px solid #E6E6E6;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
        }
        #knowl .content .detail-box .list .item {
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
            position: relative;
        }
        #knowl .content .detail-box .list .item .num {
            color: #4A4A4A;
            font-size: 15px;
            font-weight: bold;
            line-height: 21px;
        }
        #knowl .content .detail-box .list .item .line {
            position: absolute;
            top: 10px;
            right: 0;
            height: 16px;
            width: 1px;
            background-color: #E6E6E6;
        }
        #knowl .content .detail-box .list .item .txt {
            color: #909090;
            font-size: 11px;
            line-height: 16px;
        }
        #knowl .content .detail-box .code {
            padding: 18px 0;
        }
        #knowl .content .detail-box .code .erweima {
            width: 144px;
            height: 144px;
            margin: 0 auto;
        }
        #knowl .content .detail-box .code .erweima img {
            width: 100%;
            height: 100%;
            vertical-align: middle;
        }
        #knowl .content .detail-box .code .text {
            color: #909090;
            font-size: 14px;
            line-height: 20px;
            margin: 12px auto 0 auto;
        }

    </style>
</head>
<body>
<div id="knowl">
    <div class="header">
        <div class="avatar">
            <img src="{{isset($user['avatar'])?$user['avatar']:'http://img.alicdn.com/tps/TB1ld1GNFXXXXXLapXXXXXXXXXX-200-200.png'}}" alt="">
        </div>
        <div class="txt">{{isset($user['nick_name'])?$user['nick_name']:''}} 向你推荐一个数据圈</div>
    </div>
    <div class="content">
        <div class="bg-top"></div>
        <div class="detail-box">
            <div class="avatar">
                <img src="{{$coterie->avatar}}" alt="">
            </div>
            <div class="nick-name">{{$coterie->name}}</div>
            <div class="circle-master">圈主：{{$coterie->user->nick_name}}</div>
            <div class="describle">
                <div class="txt">
                    {{$coterie->description}}
                </div>
            </div>
            <div class="list">
                <div class="item">
                    <div class="num">{{$coterie->content_count}}</div>
                    <div class="line"></div>
                    <div class="txt">主题</div>
                </div>
                <div class="item">
                    <div class="num">  {{$coterie->member_count}}</div>
                    <div class="line"></div>
                    <div class="txt">成员</div>
                </div>
                <div class="item">
                    <div class="num"> {{$coterie->recommend_count}}</div>
                    <div class="line"></div>
                    <div class="txt">精华</div>
                </div>
                <div class="item">
                    <div class="num"> {{$coterie->ask_count}}</div>
                    <div class="txt">问答</div>
                </div>
            </div>
            <div class="code">
                <div class="erweima">
                    <img src="{{request('mini_code')}}" alt="">
                </div>
                <div class="text">
                    长按二维码识别，查看数据圈
                    <div> 和圈主的关系更近一步吧！</div>
                </div>
            </div>
        </div>
        <div class="bg-bottom"></div>
    </div>
</div>
</body>
</html>