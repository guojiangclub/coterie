# 果酱圈子

一款可媲美 “知识星球” 的赋能社群运营者的平台，在这里每个人都能搭建一个真正属于自己社群平台，帮助内容创作者连接铁杆粉丝，做出品质社群，实现知识变现。。

目前只有小程序版本，小程序版本有一个问题需要注意，在 IOS 端收费圈子没办法付费，这是苹果政策问题导致的，目前无法解决，请知晓。

## 效果截图

![果酱社区圈子](https://cdn.guojiang.club/coterie-1.jpg)

![果酱社区圈子](https://cdn.guojiang.club/coterie-2.jpg)

## 功能列表

- 创建圈子
- 加入圈子
- 发表主题
- 发布动态
- 嘉宾邀请
- 回答问题
- 成员管理
- 消息通知
- 分享海报


## 安装

```
git clone git@github.com:guojiangclub/coterie.git

composer install -vvv

cp .env.example .env    # 务必配置好数据库信息

php artisan vendor:publish --all

chmod -R 0777 storage

chmod -R 0777 bootstrap
 
php artisan ibrand:coterie-install
```

## 小程序

小程序源码地址：[果酱圈子小程序源码](https://gitee.com/guojiangclub/coterie.miniprogram)

## 交流

扫码添加[玖玖|彼得助理]，可获得“陈彼得”为大家精心整理的程序员成长学习路线图，以及前端、Java、Linux、Python等编程学习资料，同时还教你25个副业赚钱思维。

![玖玖|彼得助理 微信二维码](https://cdn.guojiang.club/xiaojunjunqyewx2.jpg)
