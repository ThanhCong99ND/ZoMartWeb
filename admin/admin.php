<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#0069A8" width="100%" id="AutoNumber1">
<tr><td width="45%" height="20" align="center" class="title">
Danh sách quản trị viên | <a href="?act=admin_add"><font color="#ffffff">Thêm quản trị</font></a>
</td></tr>
<tr><td>
<?
	switch ($_GET['action'])
	{
		case 'del' :
			$id = $_GET['id'];
		    $sql1="select * from user_nhanvien where id=$id limit 1";
        	$pro=mysql_fetch_assoc(mysql_query($sql1,$con));
			$sql = "delete from user_nhanvien where id='".$id."'";
			@$result = mysql_query($sql,$con);
			if ($result) 
				{
					if (file_exists("../".$pro['logo'])) unlink("../".$pro['logo']);
					if (file_exists("../".$pro['banner'])) unlink("../".$pro['banner']);
					if (file_exists("../".$pro['background'])) unlink("../".$pro['background']);
					echo "<p align=center class='err'>&#272;ã xóa thành công</p>";
				}
					else echo "<p align=center class='err'>Không th&#7875; xóa d&#7919; li&#7879;u</p>";
			break;
	}
?>

<?
	if (isset($_POST['ButDel'])) {
		$cnt=0;
		foreach ($_POST['chk'] as $id)
		{
			$sql="select * from user_nhanvien where id=$id limit 1";
        	$pro=mysql_fetch_assoc(mysql_query($sql,$con));
			@$result = mysql_query("delete from user_nhanvien where id='".$id."'",$con);
			if ($result)
			{
				$cnt++;
				    if (file_exists("../".$pro['logo'])) unlink("../".$pro['logo']);
					if (file_exists("../".$pro['banner'])) unlink("../".$pro['banner']);
					if (file_exists("../".$pro['background'])) unlink("../".$pro['background']);
			}
		}
		echo "<p align=center class='err'>&#272;ã xóa ".$cnt." ph&#7847;n t&#7917;</p>";
	}
?>
<?
	$page = $_GET["page"];
	$MAXPAGE=50;
	$p=0;
	if ($page!='') $p=$page;
	//download source code tại codefly.vn
?>


<form method="POST" action="<? echo $_SERVER[PHP_SELF]; ?>" name="frmList">
<input type="hidden" name="act" value="admin">
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
		if ($i<>$itemcurrent) $ret .= "<a href=\"".$link.$i."\" class=\"lslink\">".($i+1)."</a> ";
		else $ret .= ($i+1)." ";
	}
	return $ret;
}
	$pageindex=taotrang("select count(*) from user_nhanvien","./?act=admin"."&page=",$MAXPAGE,$page);
?>

<table cellspacing="0" cellpadding="0">
<tr>
<td class="smallfont">Trang : <? echo $pageindex; ?></td>
</tr>
</table>


<tr><td>
<!-- begin danh sach -->
<form method="POST" action="<? echo $_SERVER[PHP_SELF]; ?>" name="frmList">
<table border="1" cellpadding="2" style="border-collapse: collapse" bordercolor="#C9C9C9" width="100%" id="AutoNumber1">
<tr class="title">
<td align=center nowrap class="title"><input type="checkbox" name="chkall" onclick="chkallClick(this);"></td>
<td nowrap class="title">&nbsp;</td>
<td nowrap class="title">&nbsp;</td>
<td>ID</td>
<td>Tài khoản</td>
 
<td>Chức vụ</td>
</tr>
<? $sql=mysql_query("SELECT * FROM user_nhanvien order by id desc limit ".($p*$MAXPAGE).",".$MAXPAGE);
$i=0;
while($row=mysql_fetch_array($sql))
{
	$i++;
	if ($i%2) $color="#d5d5d5"; else $color="#e5e5e5";
?>

<tr bgcolor="<? echo $color;?>">
<td width="20" align="center" class="smallfont"><input type="checkbox" name="chk[]" value="<? echo $row['id']; ?>"></td>
<td width="20" bgcolor="<? echo $color; ?>" class="smallfont">
    <a href="./?act=admin_add&cat=<? echo $_REQUEST['cat']; ?>&page=<? echo $_REQUEST['page']; ?>&id=<? echo $row['id']; ?>">
	Sửa</a></td>
 <td width="20" bgcolor="<? echo $color; ?>" class="smallfont">
    <a href="./?act=admin&action=del&page=<? echo $_REQUEST['page']; ?>&id=<? echo $row['id']; ?>">
	Xoá</a></td>
<td><? echo $row['id'];?></td>
<td><? echo $row['user'];?></td>
 
 
<td>
 <?php if($row['level']=="1")
{?>
Admin tối cao
<?}?>

 <?php if($row['level']=="0")
{?>
Nhân viên quản lý đơn hàng trong /nhanvien
<?}?>
</td>

</tr>
<?}?>
</table>
<!-- end danh sach -->



</td></tr>
</table>
<input type="submit" value="Xóa Ch&#7885;n" name="ButDel" onclick="return confirm('B&#7841;n có ch&#7855;c ch&#7855;n mu&#7889;n xoá ?');" class="button" style="padding: 0">
<input type="hidden" name="act" value="users">
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