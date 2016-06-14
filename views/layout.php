<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<!--    if文は、開き波括弧{をコロン(:)、閉じ波括弧}をendif;に変更することができる。-->
    <title><?php if(isset($title)):echo $this->escape($title).'-';
        endif; ?>Mini Blog</title>
</head>
<body>
   <div id="header">
       <h1><a href="<?php echo $base_url; ?>/">Mini Blog</a></h1>
   </div>
   
   <div id="main">
       <?php echo $_content; ?>
   </div>
    
</body>
</html>