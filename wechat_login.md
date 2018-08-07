- 申请微信公众平台测试账号
申请公众平台测试账号很方便，直接微信登录，[登录地址](https://mp.weixin.qq.com/debug/cgi-bin/sandbox?t=sandbox/login)登录后可以看到 appId 和 appsecret。

- 登录测试账号
![title](https://leanote.com/api/file/getImage?fileId=5b693eceab6441721a0014d3)
- 关注测试账号，只有关注了测试公众号的用户，才可以进行授权操作
![title](https://leanote.com/api/file/getImage?fileId=5b693f5eab6441721a0014f0)
- 修改网页账号
![title](https://leanote.com/api/file/getImage?fileId=5b693f90ab6441721a00150a)
填入 test.larabbs.com，这个是 OAuth流程中需要提前配置好的回调域名，回调地址必须在这个域名下。
![title](https://leanote.com/api/file/getImage?fileId=5b693fa3ab6441702d0015dc)
- 测试OAuth流程
因为是公众平台测试账号，所以我们首先需要下载 [微信web开发者工具](https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1455784140)，方便我们接下来的调试。
- 扫码登录
![title](https://leanote.com/api/file/getImage?fileId=5b694002ab6441721a001535)
- 微信网页授权
![title](https://leanote.com/api/file/getImage?fileId=5b69401dab6441721a001539)
接下来，我们尝试一下 微信网页授权 的流程。下面这个链接为微信发起 OAuth 的跳转地址。
```
https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect

APPID 测试账号中的appID，填写自己账号的 appID
REDIRECT_URI 用户同意授权后的回调地址，填写 http://larabbs.test
SCOPE 应用授权作用域，填写 snsapi_userinfo
STATE 随机参数，可以不填，我们保持 STATE 即可。

这是替换后的
https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe0ba316xxxxxxx&redirect_uri=http://larabbs.test&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
在开发者工具中，访问该链接，可以看到微信授权页面
```
![title](https://leanote.com/api/file/getImage?fileId=5b69408cab6441721a001550)
点击OK
![title](https://leanote.com/api/file/getImage?fileId=5b6940edab6441702d001633)
我们成功的跳转回了 REDIRECT_URI，注意url中可以看到code参数。好了我们已经完成了 OAuth 流程中获取授权码的步骤。
- 获取授权码
```
https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code

APPID 测试账号中的appID，填写自己账号的 appID
SECRET 测试账号中的secret，填写自己账号的 secret
code 上一步获取的 code

https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx353901f6xxxxx&secret=d4624c36b6795d1d99dxxxxxxxx&code=0813AoG21n9C2O1yfxH21t4nG213AoGH&grant_type=authorization_code

使用 PostMan 访问该链接，获取到了 access_token，注意微信同时返回了 open_id，微信access_token 和 open_id 一起请求用户信息。
```
![title](https://leanote.com/api/file/getImage?fileId=5b69413cab6441721a001585)
- 通过access_token获取个人信息
```
https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
替换链接中的 ACCESS_TOKEN 和 OPENID，使用 PostMan 访问
```
![title](https://leanote.com/api/file/getImage?fileId=5b694188ab6441702d001687)

























