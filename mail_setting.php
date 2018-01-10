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
        <th>登録済み脆弱性情報</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
<?php
include 'include/db_connect.php';
$company_query = mysqli_query($link,"select code,company_name from company_info");
while ($company_row = mysqli_fetch_row($company_query)) {
  $company_code = $company_row[0];
  $company_name = $company_row[1];
  echo "<tr>";
  echo "<td>".$company_name."</td>";
  echo "<td>";
  $mail_query = mysqli_query($link,"select user_mail from user_mail_setting where company_code = '{$company_code}'");
  while ($mail_row = mysqli_fetch_row($mail_query)) {
      echo $mail_row[0]."<br/>";
  }
  echo '<form action="./mail_address_setting.php" method="post">';
  echo '<button class="btn btn-info" input type="hidden" value="'.$company_code.'" name="company_code" type="submit">変更</button>';
  echo '</form>';
  echo "</td>";
  echo "<td>";
  $sent_vendor_before = "";
  $sent_query = mysqli_query($link,"select sent_vendor,sent_product from user_sent_setting where company_code = '{$company_code}' ORDER BY sent_vendor");
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