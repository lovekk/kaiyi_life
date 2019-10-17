<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $Title; ?> - <?php echo $Powered; ?></title>
<link rel="stylesheet" href="./css/install.css?v=9.0" />
</head>
<body>
<div class="wrap">
  <?php require './templates/header.php';?>
  <div class="section">
    <div class="main cc">
      <pre class="pact" readonly="readonly">
          凯易生活软件

          凯易生活是由徐州凯易商务服务有限公司推出的线上社区服务平台。

          凯易生活以互联网+社区服务的新型电商模式，为社区居民提供更好的服务。

          凯易生活社区服务现已做到徐州市区大小社区无差别覆盖，与包括徐州民生集团，农夫山泉等在内的多家国内知名企业进行深度合作，共同打造这一惠民工程。

          -----------------------------------------------------
          开发团队: 萤之梦团队
          邮    箱: 37312587@qq.com
          发布时间: 2019.10.10

</pre>
    
    </div>
    <div class="bottom tac"> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?step=2" class="btn">继 续</a> </div>
  </div>
</div>
<?php require './templates/footer.php';?>
</body>
</html>