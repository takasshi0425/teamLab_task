<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>入社前課題</title>
</head>
<body>
<?php
class dat{
    public $image;
    public $name;
    public $explanation;
    public $price;
}
?>
<form action="index.php" method="post">
  <table border="1">
    <tr>
      <td>商品名</td>
      <td><input type="text" name="name"></td>
    </tr>
    <tr>
	  <td>商品画像</td>
	  <td><input type="file" name="img"></td>
	</tr>
    <tr>
      <td>商品説明</td>
      <td><textarea name="example" cols="50" rows="10"></textarea></td>
    </tr>
    <tr>
      <td>価格</td>
      <td><input type="text" name="price"></td>
    </tr>
  </table>
  <input type="submit" name="botan" value="send">
</form>
</body>
</html>
