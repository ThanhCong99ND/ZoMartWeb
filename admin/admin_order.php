<?
echo $_SESSION["session_message"];
$_SESSION["session_message"] = "";
?>
<table height="28" cellSpacing="0" cellPadding="0" width="100%" border="0">
      <tr align=center>
        <td class="title" width="100%">&#272;&#417;n hàng : &nbsp;|&nbsp
	</td>
      </tr>
    </table>
<?
	switch ($_GET['action'])
	{
		case 'del' :
			$id = $_GET['id'];
				$sql = "delete from orders where orders_id='".$id."'";
				$result = mysql_query($sql,$con);
				if ($result)
		          {
                    $sql_del=mysql_query("delete from orderdetail where ordersdetail_ordersid='".$id."' ");
					echo "<p align=center class='err'>&#272;ã xóa thành công</p>";
		          }
				
				else 
		          {
					echo "<p align=center class='err'>Không th&#7875; xóa d&#7919; li&#7879;u</p>";
				  }
			break;
	}
?>

<?
	if (isset($_POST['ButDel'])) {
		$cnt=0;
		foreach ($_POST['chk'] as $id)
		{
				@$result = mysql_query("delete from orders where orders_id='".$id."'",$con);
				if ($result)
			    {
					$sql_del=mysql_query("delete from orderdetail where ordersdetail_ordersid='".$id."' ");
				}
				$cnt++;
		}
		echo "<p align=center class='err'>&#272;ã xóa ".$cnt." ph&#7847;n t&#7917;</p>";
	}
?>

<?php
if (isset($_POST['xuly'])) {
		$cnt=0;
		foreach ($_POST['chk'] as $id)
		{
			$pro=GetProductInfo($id);
			if ($pro)
			{
				@$result = mysql_query("update orders set active=1 where orders_id='".$id."'",$con);
				if ($result) {
					$cnt++;

				}
			}
		}
		echo "<p align=center class='err'>Đã xử lý ".$cnt." đơn hàng</p>";
	}
	?>
	
	<?
	$page = $_GET["page"];
	$p=0;
	if ($page!='') $p=$page;
	$where="1=1";
	//if ($_REQUEST['status']!='') $where="products_status=".$_REQUEST['status']." ";
	if ($_REQUEST['cat']!='') $where="orders_cat=".$_REQUEST['cat'];
?>
<form method="POST" name="frmList" action="index.php">
<input type="hidden" name="act" value="order">
<input type=hidden name="page" value="<? echo $page; ?>">
<?
function taotrang($sql,$link,$nitem,$itemcurrent)
{	global $con;
	$ret="";
	$result = mysql_query($sql, $con) or die('Error' . mysql_error());
	$value = mysql_fetch_array($result);
	$plus = (($value['cnt'] % $nitem)>0);
	for ($i=0;$i<($value[0] / $nitem) + plus;$i++)
	{
		if ($i+1<>$itemcurrent) $ret .= "<a href=\"".$link.($i+1)."\" class=\"lslink\">".($i+1)."</a> ";
		else $ret .= ($i+1)." ";
	}
	return $ret;
}
	$pageindex=taotrang("select count(*) from orders","./?act=order&cat=".$_REQUEST['cat']."&page=",10,$page);
?>

<table cellspacing="0" cellpadding="0">
<tr>
<td class="smallfont">Trang : <? echo $pageindex; ?></td>
</tr>
</table>

<table border="1" cellpadding="2" style="border-collapse: collapse" bordercolor="#C9C9C9" width="100%" id="AutoNumber1">
  <tr>
    <td align=center nowrap class="title"><input type="checkbox" name="chkall" onclick="chkallClick(this);"></td>
    <td colspan="2" nowrap class="title">Trạng thái</td>
    <td align="center" nowrap class="title"><b>Chi ti&#7871;t</b></td>
    <td align="center" nowrap class="title"><b>Mã &#273;&#417;n hàng</b></td>
    <td align="center" nowrap class="title"><b>S&#7889; l&#432;&#7907;ng s&#7843;n ph&#7849;m</b></td>    
    <td align="center" nowrap class="title"><b>Tên khách hàng</b></td>
    <td align="center" nowrap class="title"><b>&#272;&#7883;a ch&#7881;</b></td>
    <td align="center" nowrap class="title"><b>&#272;i&#7879;n tho&#7841;i</b></td>    
    <td align="center" nowrap class="title"><b>Email</b></td>	
	<td align="center" nowrap class="title"><b>Gian hàng</b></td>
    <td align="center" nowrap class="title"><b>Date</b></td>

  </tr>
  
  <?php
            $MAXPAGE=10;
			$page = $_GET["page"];
        	$sql="select * from orders order by orders_date desc limit ".($p*$MAXPAGE).",".$MAXPAGE;
        	$result=mysql_query($sql,$con);
        	$i=0;
           	while(($row=mysql_fetch_array($result)))
			{
			$i++;
			if ($i%2) $color="#d5d5d5"; else $color="#e5e5e5";
			$cust=GetCustomerInfo($row['orders_customer_id']);
  ?>
  
  <tr>
    <td bgcolor="<? echo $color; ?>" class="smallfont">
    <p align="center"><input type="checkbox" name="chk[]" value="<? echo $row['orders_id']; ?>"></td>
    <td bgcolor="<? echo $color; ?>" class="smallfont">
    <p align="center">
    <a <? echo $page?>"><?if($row['active']=='0'){?><img src="/quantri/css/un_active.png"><?}else{?><img src="/quantri/css/active.png"><?}?></a></td>
    <td bgcolor="<? echo $color; ?>" class="smallfont">
    <p align="center">
    <a onclick="return confirm('B&#7841;n có ch&#7855;c ch&#7855;n mu&#7889;n xoá ?');" href="./?act=order&action=del&id=<? echo $row['orders_id']; ?>">Delete</a></td>
    <td bgcolor="<? echo $color; ?>" class="smallfont" align="center"><input type="button" name="butDetail" value="Chi tiết" class="button" onclick="javascript:window.location='./?act=orderdetail&orderid=<? echo $row['orders_id']; ?>'"></td>
    <td bgcolor="<? echo $color; ?>" align="center" class="smallfont"><? echo $row['orders_id']; ?>&nbsp;</td>
    <td bgcolor="<? echo $color; ?>" align="center" class="smallfont"><? echo CountRecord('orderdetail','ordersdetail_ordersid='.$row['orders_id']); ?>&nbsp;</td>
    <td bgcolor="<? echo $color; ?>" class="smallfont"><? echo $row['orders_name']; ?>&nbsp;</td>
    <td bgcolor="<? echo $color; ?>" class="smallfont"><? echo $row['orders_address']; ?>&nbsp;</td>
    <td bgcolor="<? echo $color; ?>" class="smallfont"><? echo $row['orders_phone']; ?>&nbsp;</td>
    <td bgcolor="<? echo $color; ?>" class="smallfont"><? echo $row['orders_email']; ?>&nbsp;</td>
	<td bgcolor="<? echo $color; ?>" class="smallfont"><a href="../<? echo $row['orders_user']; ?>" target="_blank"><? echo $row['orders_user']; ?></a>&nbsp;</td>
    <td bgcolor="<? echo $color; ?>" class="smallfont"><? echo $row['orders_date']; ?>&nbsp;</td>
  </tr>
  <?
              	}
  ?>
</table>
<input type="submit" value="Xóa Ch&#7885;n" name="ButDel" onclick="return confirm('B&#7841;n có ch&#7855;c ch&#7855;n mu&#7889;n xoá ?');" class="button">
<input type="submit" value="Xử lý" name="xuly" class="button">
</form>
<script language="JavaScript">
function chkallClick(o) {
  	var form = document.frmList;
	for (var i = 0; i < form.elements.length; i++) {
		if (form.elements[i].type == "checkbox" && form.elements[i].name!="chkall") {
			form.elements[i].checked = document.frmList.chkall.checked;
		}
	}
}
</script>