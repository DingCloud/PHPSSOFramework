# PHPSSOFramework
## 简介

PHPSSOFramework - 一款基于PHP 开发的SSO服务端框架

## 支持功能

* [ ] 用户本域登录/注册
* [ ] 多用户管理/异步登录注册（AJAX）
* [ ] 同父域Cookie共享
* [ ] 跨父域Cookie共享
* [ ] Session安全支持
* [ ] Token访问令牌鉴权
* [ ] CSRF安全防护系统
* [ ] JSONP跨域支持
* [ ] 前台用户密码重置
* [ ] 开箱即用（OOBE）支持


## 部署方法

*   由于尚未设计本系统的OOBE引导程序，首次运行的数据结构导入操作请用户自行完成！

## 重要提示


*   统一身份认证（Single Sign On）协议需要OpenSSL支持，请确保在PHP.INI中开启OpenSSL支持扩展。

## 运行环境

*   Web服务器：IIS/Apache/Nginx 已测试可用
*   PHP后端建议版本：5.5及以上，经测试兼容最新PHP7.2
*   MySQL建议版本：5.5及以上，经测试兼容最新MySQL5.7
*   RestAPI支持：通过UrlRewrite实现
*   PHP所需扩展：bcmath,bz2,ctype,curl,dom,gd,hash,iconv,json,mbstring,mysqli,openssl,pdo,pdo_mysql,session,xml,zip

## 友好的开源协议

PHPSSOFramework遵循MIT开源协议发布。