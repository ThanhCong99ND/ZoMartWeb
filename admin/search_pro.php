<?
if (isset($_REQUEST['act']))
{
?><br>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 id="table35" style="line-height: 120%; text-align: justify">
<tr>
<td align="center" class="title" width="120"><font color="#FFF"><b>Hình ảnh</b></font></td>
<td align="center" class="title" width="500"><font color="#FFF"><b>Sản phẩm</b></font></td>
<td align="center" class="title" width="100">
<p align="left">
<font color="#FFF"><b>Chức năng</b></font></td>
	</tr>
</table>
<TABLE cellSpacing=0 cellPadding=10 width="100%" border=0 id="table35" style="line-height: 120%; text-align: justify">
<?
if ($_REQUEST['cat_id']!='') $cat=killInjection($_REQUEST['cat_id']);
$catallsub=GetCatAllID($cat);
	$where="1=1";
	$keywords=killInjection($_REQUEST['keywords']);
	if ($keywords!='')
	{
		$where.=" and (name like '%".$keywords."%' or content like '%".$keywords."%'";
		$where.=") ";
	}
	if ($_REQUEST['cat_id']!='')	$where.=" and cat in (".$catallsub."0)";
	if ($_REQUEST['manufacturers_id']!='')	$where.=" and providers_id=".$_REQUEST['manufacturers_id'];
	if ($_REQUEST['pfrom']!='')	$where.=" and price>=".$_REQUEST['pfrom'];
	if ($_REQUEST['pto']!='') $where.=" and price_special<=".$_REQUEST['pto'];
	if ($_REQUEST['dfrom']!='')	$where.=" and products_date_added>=".$_REQUEST['dfrom'];
	if ($_REQUEST['dto']!='') $where.=" and products_date_added<=".$_REQUEST['dto'];
	
	$MAXPAGE=20;
	$p=0;
	if ($_REQUEST['p']!='') $p=$_REQUEST['p'];
	
	$result = mysql_query("select count(*) from products where $where ",$con);
	$total=mysql_fetch_row($result);

	$sql="select * from products where $where limit ".$p*$MAXPAGE.",".$MAXPAGE;
	$result = mysql_query($sql,$con);
	
	while (($row=mysql_fetch_assoc($result)))
	{
		$danhmuc=mysql_query("select * from cat_mem where id='".$row['cat_mem']."'");
	    $xuat=mysql_fetch_assoc($danhmuc);
		echo '<tr >';
		echo '<td align="center" width="122"><IMG width="80"  src="../'.$row['image'].'"></td><td align="left" width="572"><a id="xemnhanhsp" href="/index1.php?home=products&views='.$row["id"].'"><b><font color="#000000">'.$row['name'].'</font></b></a> <br> Shop: '.$row["user"].'<br> Giá: '.number_format($row["price_special"]).'đ</td><td align="center" width="99" ><a href="./?act=product_m&id='.$row['id'].'">Sửa</a> | 
		<a onclick="return confirm("Bạn có muốn xóa sản phẩm này không");" href="./?act=product&action=del&id='.$row['id'].'">Xoá</a></td>';
		echo '</tr>';
	}
	settype($total[0],int);
?>
</table>
<?
	$s="act=search&act=search&keywords=$keywords&search_in_description=".$_REQUEST['search_in_description']."&categories_id=".$_REQUEST['categories_id']."&manufacturers_id=".$_REQUEST['manufacturers_id']."&pfrom=".$_REQUEST['pfrom']."&pto=".$_REQUEST['pto']."&dfrom=".$_REQUEST['dfrom']."&dto=".$REQUEST['dto'];
?>

<form id="search1" name="search1" style="word-spacing: 0; margin: 0" method="GET" action="index.php?<? echo $s; ?>">
<input type="hidden" name="menu" value="<? echo $_REQUEST['menu']; ?>">
<input type="hidden" name="act" value="<? echo $_REQUEST['act']; ?>">
<input type="hidden" name="keywords" value="<? echo $_REQUEST['keywords']; ?>">
<input type="hidden" name="search_in_description" value="<? echo $_REQUEST['search_in_description']; ?>">
<input type="hidden" name="categories_id" value="<? echo $_REQUEST['categories_id']; ?>">
<input type="hidden" name="manufacturers_id" value="<? echo $_REQUEST['manufacturers_id']; ?>">
<input type="hidden" name="pfrom" value="<? echo $_REQUEST['pfrom']; ?>">
<input type="hidden" name="pto" value="<? echo $_REQUEST['pto']; ?>">
<input type="hidden" name="dfrom" value="<? echo $_REQUEST['dfrom']; ?>">
<input type="hidden" name="dto" value="<? echo $_REQUEST['dto']; ?>">
<input type="hidden" id="trang" name="p" value="1">
<TABLE cellSpacing=10 cellPadding=0 width="100%" border=0 id="table35" style="line-height: 120%; text-align: justify">
<?
$pages=count_page($total[0],$MAXPAGE);
echo '<tr><td colspan="2" align="center"><hr class="fieldkey" width="100%" SIZE=1></td></tr>';
echo '<tr><td class="smallfont" align="left">Trình bày <b>'.($p+1).'</b> &#273;&#7871;n <b>'.(int)($total[0]/$MAXPAGE+1).'</b> (trong <b>'.$total[0].'</b> sản phẩm)</td>';
echo '<td class="smallfont" align="right">K&#7871;t qu&#7843;: ';
//$param="act=search&keywords=$keywords&search_in_description=".$_REQUEST['search_in_description']."&categories_id=".$_REQUEST['categories_id']."&manufacturers_id=".$_REQUEST['manufacturers_id']."&pfrom=".$_REQUEST['pfrom']."&pto=".$_REQUEST['pto']."&dfrom=".$_REQUEST['dfrom']."&dto=".$REQUEST['dto'];
if ($p>1) echo '<a title="&#272;&#7847;u tiên" href="#" onclick="javascript:changepage(0);return false;">[&lt;]</a> ';
if ($p>0) echo '<a title="V&#7873; tr&#432;&#7899;c" href="#" onclick="javascript:changepage('.($p-1).');return false;">[&lt;&lt;]</a> ';
$from=($p-10>0?$p-10:0);
$to=($p+10<$pages?$p+10:$pages);
for ($i=$from;$i<$to;$i++)
{
	//if ($i!=$p) echo '<a href="./?menu=search&'.$param.'&p='.$i.'">'.($i+1).' </a>';
	if ($i!=$p) echo '<a href="#" onclick="javascript:changepage('.$i.');return false;">'.($i+1).' </a>';
	else echo '<b>'.($i+1).'</b> ';
}
if ($p<$i-1) echo '<a title="Ti&#7871;p theo" href="#" onclick="javascript:changepage('.($p+1).');return false;">[&gt;&gt;]</a> ';
if ($p<$pages-1) echo '<a title="Cu&#7889;i cùng" href="#" onclick="javascript:changepage('.($pages-1).');return false;">[&gt;]</a> '; 
echo '</td></tr></table>';
?>
</form>

<script>
	function changepage(num)
	{	
		document.all.trang.value=num;
		search1.submit();
	}
</script>

<?
}
else
{
?>            
<TABLE border="0" cellpadding="10" cellspacing="1" width="100%" id="table1">
<TR><TD class="DialogBox">
<FORM name="searchform" action="./" method="GET">
<table cellSpacing="0" cellPadding="2" width="100%" border="0" id="table2">
	<tr>
		<td width="35%" align="right"><font face="Tahoma"><span style="font-size: 8.5pt">T&#7915; khóa:</span></font></td>
		<td class="fieldValue" width="55%">
		<span style="font-size: 8.5pt"><font size="1" face="Tahoma">
		<input name="keywords" size="255" style="width: 200; height:22"></font></span></td>
	</tr>
	<tr>
		<td width="35%" align="right">&nbsp;</td>
		<td class="fieldValue" width="55%">
		<span style="font-size: 8.5pt"><font face="Tahoma">
		<input type="checkbox" value="1" name="search_in_description"> Ch&#7881; tìm 
		trong ph&#7847;n mô t&#7843; s&#7843;n ph&#7849;m</font></span></td>
	</tr>
	<tr>
		<td width="35%" align="right">&nbsp;</td>
		<td class="fieldValue" width="55%">
                                    <font face="Verdana" size="1">
                					<span style="font-size: 8.5pt">
									<font face="Tahoma">
                <input type=submit value="Tìm ki&#7871;m" class=buttonorange onmouseover="this.className='buttonblue'" onmouseout="this.className='buttonorange'"></font></span></font></td>
	</tr>
	<tr>
		<td width="35%" align="right">&nbsp;</td>
		<td class="fieldValue" width="55%">&nbsp;</td>
	</tr>
	<tr>
		<td width="35%" align="right"><font face="Tahoma"><span style="font-size: 8.5pt">Trong danh m&#7909;c:</span></font></td>
		<td class="fieldValue" width="55%">
		<span style="font-size: 8.5pt"><font face="Tahoma">
		<select name="categories_id" size="1" style="width: 200">
		<option selected value="">[Toàn b&#7897; danh m&#7909;c]</option>
<? 
	$cats=GetListCategory(17);
	foreach ($cats as $cat)
	{
		echo '<option value="'.$cat[0].'">'.$cat[1].'</option>';
	}
?>		
		</select></font></span></td>
	</tr>
	<tr>
		<td width="35%" align="right"><font face="Tahoma"><span style="font-size: 8.5pt">Giá (&gt;=):</span></font></td>
		<td class="fieldValue" width="55%"><span style="font-size: 8.5pt">
		<font face="Tahoma"><input name="pfrom"></font></span></td>
	</tr>
	<tr>
		<td width="35%" align="right"><font face="Tahoma"><span style="font-size: 8.5pt">Giá (&lt;=):</span></font></td>
		<td class="fieldValue" width="55%"><span style="font-size: 8.5pt">
		<font face="Tahoma"><input name="pto"></font></span></td>
	</tr>
	<tr>
		<td width="35%" align="right"><font face="Tahoma"><span style="font-size: 8.5pt">Ngày (&gt;=):</span></font></td>
		<td class="fieldValue" width="55%">
		<span style="font-size: 8.5pt"><font face="Tahoma">
		<input name="dfrom"> (tháng/ngày/n&#259;m)</font></span></td>
	</tr>
	<tr>
		<td width="35%" align="right"><font face="Tahoma"><span style="font-size: 8.5pt">Ngày (&lt;=):</span></font></td>
		<td class="fieldValue" width="55%">
		<span style="font-size: 8.5pt"><font face="Tahoma">
		<input name="dto"> (tháng/ngày/n&#259;m)</font></span></td>
	</tr>
</table>

<input type="hidden" name="act" value="search">

<input type="hidden" name="menu" value="search">

</FORM>
	</TD></TR>
</TABLE>
<?
}
?>