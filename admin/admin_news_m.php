<?
include_once("fckeditor/fckeditor.php") ;
?>
<?
//Check user's Browser
if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE"))
	echo "<script language=JavaScript src='../inv/scripts/editor.js'></script>";
else
	echo "<script language=JavaScript src='../inv/scripts/moz/editor.js'></script>";
?>

<script>
function submitForm()
	{
	document.forms.FormLoaiSP.elements.content.value = oEdit1.getHTMLBody();
	document.forms.FormLoaiSP.submit();
	}
</script>

<?
$path = "../images/news";
$pathdb = "images/news";
if (isset($_POST['butSaveLoai'])) {
	$name=$_POST['name'];
	$short=$_POST['short'];
	$desc=$_POST['desc'];
	$cat=$_POST['cat'];	
	$thutu = $_POST['thutu'];
	$date=date("d-m-Y");

	$catinfo=GetCategoryInfo($categories_id);
	$err="";
	if ($name=="") $err .=  "Xin nh&#7853;p tên s&#7843;n ph&#7849;m <br>";
	$err.=CheckUpload($_FILES["txtImage"],".jpg;.gif;.png;.bmp",5000*1024,0);
	$err.=CheckUpload($_FILES["txtImageLarge"],".jpg;.gif;.png;.bmp",5000*1024,0);

	if ($err=='')
	{
	if (!empty($_POST['id'])) {
			$oldid = $_POST['id'];
			$sql = "update news set name='".$name."', short='".$short."', content='".$content."', cat_id='".$cat."', thutu='".$thutu."' where id='".$oldid."'";
		}else {
			$sql = "insert into news (name, short, content , cat_id, thutu,date) values ('".$name."','".$short."','".$content."','".$cat."','".$thutu."','".$date."')";
		}
		//echo $sql;
		//exit();
		if (mysql_query($sql,$con)) 
		{
			if(empty($_POST['id'])) {
				$oldid = mysql_insert_id();
				}
		
			$sqlUpdateField = "";
			
			$extsmall=GetFileExtention($_FILES['txtImage']['name']);
			if (MakeUpload($_FILES['txtImage'],"$path/news_s$oldid$extsmall"))
			{
				@chmod("$path/news_s$oldid$extsmall", 0777);
				$sqlUpdateField = " image='$pathdb/news_s$oldid$extsmall' ";
			}

			$extlarge=GetFileExtention($_FILES['txtImageLarge']['name']);
			if (MakeUpload($_FILES['txtImageLarge'],"$path/news_l$oldid$extlarge"))
			{
				@chmod("$path/news_l$oldid$extlarge", 0777);
				if($sqlUpdateField != "") $sqlUpdateField .= ",";
				$sqlUpdateField .= " image_large='$pathdb/news_l$oldid$extlarge' ";
			}
			if($sqlUpdateField!='')
			{
				$sqlUpdate = "update news set $sqlUpdateField where id='".$oldid."'";
				mysql_query($sqlUpdate,$con);
			}
		}	
		else {
			$err =  "Không th&#7875; c&#7853;p nh&#7853;t";
		}
  	}

  	if ($err=='') echo '<script>window.location="index.php?act=news&status='.$_REQUEST['status'].'&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page'].'&code=1"</script>';
  	else echo "<p align=center class='err'>".$err."</p>";
} else {
?>

<?
	if (isset($_GET['id'])) {
		$oldid=$_GET['id'];
		$page = $_GET['page'];
		$sql = "select * from news where id='".$oldid."'";
		if ($result = mysql_query($sql,$con)) {
			$row=mysql_fetch_array($result);
			$name=$row['name'];
			$image=$row['image'];
			$categories_id = $row['cat_id'];
			$imagelarge=$row['image_large'];
			$short=$row['short'];
			$desc=$row['content'];
			$thutu=$row['thutu'];
		}
	}
}

?>

<pre id="idTemporary" name="idTemporary" style="display:none">
<?
if(isset($content)) 
	{
	$sContent=stripslashes($content);//remove slashes (/)	
	echo $sContent;
	}
?>
</pre>



<pre id="idTemporaryen" name="idTemporaryen" style="display:none">
<?
if(isset($descen)) 
	{
	$sContent=stripslashes($descen);//remove slashes (/)	
	echo htmlentities($sContent);
	}
?>
</pre>

<script language="Javascript1.2"><!-- // load htmlarea
_editor_url = "htmlarea/";                     // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
  document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
  document.write(' language="Javascript1.2"></scr' + 'ipt>');  
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
// --></script>

<form method="post" name="FormLoaiSP" enctype="multipart/form-data" action="index.php?">
<input type="hidden" name="content" id="content">
<input type="hidden" name="act" value="news_m">
<input type="hidden" name="id" value="<? echo $_REQUEST['id']; ?>">
<input type="hidden" name="page" value="<? echo $_REQUEST['page']; ?>">
<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#0069A8" width="100%" id="AutoNumber1">
  <tr>
    <td width="45%" class="title" align="center">Thêm m&#7899;i / C&#7853;p nh&#7853;t : 
	S&#7843;n ph&#7849;m</td>
  </tr>
  <tr>
    <td width="45%">
    <table border="0" cellpadding="2" bordercolor="#111111" width="100%" id="AutoNumber2" cellspacing="0">
<?    
if ($image!='')
{
	echo '<tr><td colspan="3" align="center"><img border="0" src="../'.$image.'"></td></tr>';
}
?>		
     <tr>
        <td width="15%" class="smallfont">
        <p align="right">Tên s&#7843;n ph&#7849;m</td>
        <td width="1%" class="smallfont" align="center">
        <font color="#FF0000">*</font></td>
        <td width="83%" class="smallfont">
		<INPUT value="<? echo $name; ?>" TYPE="text" NAME="name" CLASS=textbox size="89"></td>
      </tr>

      <tr>
        <td width="15%" class="smallfont">
        <p align="right">Hình nh&#7887;</td>
        <td width="1%" class="smallfont" align="center">
        <font color="#FF0000">*</font></td>
        <td width="83%" class="smallfont">
		<INPUT TYPE="file" NAME="txtImage" CLASS=textbox size="34"></td>
      </tr>
     <tr>
        <td width="15%" class="smallfont">
        <p align="right">Hình l&#7899;n</td>
        <td width="1%" class="smallfont" align="center">
        &nbsp;</td>
        <td width="83%" class="smallfont">
		<INPUT TYPE="file" NAME="txtImageLarge" CLASS=textbox size="34"></td>
      </tr>
        <td width="15%" class="smallfont" align="right">
        Mô tả ngắn</td>
        <td width="1%" class="smallfont" align="center">
        &nbsp;</td>
        <td width="83%" class="smallfont">
<textarea style="width:462; height:99" name="short" cols="4" cols="28"><? echo $short; ?></textarea>
		</td>
      </tr>














		<tr>
        <td width="15%" class="smallfont" align="right">
        Mô t&#7843; s&#7843;n ph&#7849;m</td>
        <td width="1%" class="smallfont" align="center">
        &nbsp;</td>
        <td width="83%" class="smallfont">
   <?php
$oFCKeditor = new FCKeditor('desc') ;
$oFCKeditor->BasePath = 'fckeditor/' ;
$oFCKeditor->Value = $desc ;
$oFCKeditor->Create() ;
?>     
		</td>
      </tr>














      <tr>
        <td width="15%" class="smallfont" align="right">
        Thu&#7897;c danh m&#7909;c</td>
        <td width="1%" class="smallfont" align="center">&nbsp;
        </td>        
        <td width="83%" class="smallfont">
		<select size="1" name="cat" >
<?
		$cats=GetListCatNews_admin(0);
		foreach ($cats as $cat)
		{
			if ($cat[0]==$categories_id)
				echo "<option value=".$cat[0]." selected>".$cat[1]."</option>";
			else
				echo "<option value=".$cat[0].">".$cat[1]."</option>";
		}
?>		
		</select>
		</td>
      </tr>
     <tr>
        <td width="15%" class="smallfont" align="right">
        Th&#7913; t&#7921; s&#7855;p x&#7871;p</td>
        <td width="1%" class="smallfont" align="right">&nbsp;
        </td>
        <td width="83%" class="smallfont">
		<INPUT value="<? echo $thutu; ?>" TYPE="text" NAME="thutu" CLASS=textbox size="20"></td>
      </tr>
      <tr>
        <td width="15%" class="smallfont">
		<p align="right">
		<INPUT TYPE="submit" NAME="butSaveLoai" VALUE="C&#7853;p nh&#7853;t" CLASS=button onclick="submitForm()">&nbsp;</td>
        <td width="1%" class="smallfont" align="center">
		&nbsp;</td>
        <td width="83%" class="smallfont"><p align="left">&nbsp;<INPUT TYPE="reset" CLASS=button value="Nh&#7853;p l&#7841;i"></td>
      </tr>
    </table>
    </td>
  </tr>
  </table>
</form>