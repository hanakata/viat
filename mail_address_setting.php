<?php
  include 'include/header.php';
?>
<div class="container"  style="padding:30px 0 0 0">
<h4>顧客一覧</h4>
<table class="table table-striped">
    <thead>
      <tr>
        <th>顧客名</th>
        <th>送信先メールアドレス</th>
        <th>登録済み配信脆弱性情報</th>
      </tr>
    </thead>
    <tbody>
<?php
$change_company_code = $_POST['company_code'];
include 'include/db_connect.php';
$company_query = mysqli_query($link,"select code,company_name from company_info");
while ($company_row = mysqli_fetch_row($company_query)) {
  $company_code = $company_row[0];
  $company_name = $company_row[1];
  echo "<tr>";
  echo "<td>".$company_name."</td>";
  echo "<td>";
  $mail_query = mysqli_query($link,"select user_mail from user_setting where company_code = '{$company_code}' LIMIT 1");
  if ($company_code == $change_company_code){
    while ($mail_row = mysqli_fetch_row($mail_query)) {
        echo '<form action="./mail_address_regist.php" method="post">';
        echo '<input type="text" name="mail_address" value="'.$mail_row[0].'" class="form-control" autofocus>';
        echo '<input type="hidden" name="company_code" value="'.$company_code.'">';
        echo '<button class="btn btn-info" input type="text" value="'.$mail_row[0].'" type="submit">確定</button>';
        echo '</form>';
    }
  }else{
    while ($mail_row = mysqli_fetch_row($mail_query)) {
        echo $mail_row[0]."<br/>";
    }
  }
  echo "</td>";
  echo "<td>";
  $sent_vendor_before = "";
  $sent_query = mysqli_query($link,"select sent_vendor,sent_product from user_setting where company_code = '{$company_code}' ORDER BY sent_vendor");
  while ($sent_row = mysqli_fetch_row($sent_query)) {
      if ($sent_vendor_before != $sent_row[0]){
        echo "<b>".$sent_row[0]."</b><br/>";
        $sent_vendor_before = $sent_row[0];
      }
      echo $sent_row[1]."<br/>";
  }
  echo "</td>";
  echo "</tr>";
}

?>
    </tbody>
</table>
</div>