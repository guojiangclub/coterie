# 果酱圈子

一款可媲美 “知识星球” 的赋能社群运营者的平台，在这里每个人都能搭建一个真正属于自己社群平台。

目前只有小程序版本，未来会提供 uniapp 版本。小程序版本有一个问题需要注意，在 IOS 端收费圈子没办法付费，这是苹果政策问题导致的，目前无法解决，请知晓。

## 体验

![果酱社区圈子](https://cdn.guojiang.club/readme%E6%9E%9C%E9%85%B1%E7%A4%BE%E5%8C%BA%E5%9C%88%E5%AD%90.jpg)

## 安装

```
git clone git@gitlab.guojiang.club:guojiangclub/coterie.api.git

composer install -vvv

cp .env.example .env    # 务必配置好数据库信息

php artisan vendor:publish --all

chmod -R 0777 storage

chmod -R 0777 bootstrap
 
php artisan ibrand:coterie-install
```

## 小程序

小程序源码地址：[果酱圈子小程序源码](http://gitlab.guojiang.club:8090/guojiangclub/coterie.miniprogram)