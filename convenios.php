<?php session_start();
  if (isset($_GET["order"])) $order = @$_GET["order"];
  if (isset($_GET["type"])) $ordtype = @$_GET["type"];

  if (isset($_POST["filter"])) $filter = @$_POST["filter"];
  if (isset($_POST["filter_field"])) $filterfield = @$_POST["filter_field"];
  $wholeonly = false;
  if (isset($_POST["wholeonly"])) $wholeonly = @$_POST["wholeonly"];

  if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>dric -- convenios</title>
<meta name="generator" http-equiv="content-type" content="text/html;charset=utf-8"/>
<link rel="stylesheet" href="./recursos/css/main.css">
</head>
<body>
<table class="bd" width="100%"><tr><td class="hr"><h2>Convenios de la DRIC</h2></td></tr></table>
<table class="bd" width="100%"><tr><td class="hr"><h3>Solo podrá descargar los convenios a partir de la gestión 2005</h3></td>
</tr></table>
<?php
  $conn = connect();
  $showrecs = 20;
  $pagerange = 10;

  $a = @$_GET["a"];
  $recid = @$_GET["recid"];
  $page = @$_GET["page"];
  if (!isset($page)) $page = 1;

  $sql = @$_POST["sql"];
  switch ($sql) {
    case "insert":
      sql_insert();
      break;
    case "update":
      sql_update();
      break;
  }

  switch ($a) {
//    case "add":
  //    addrec();
    //  break;
    case "view":
      viewrec($recid);
      break;
    case "edit":
      editrec($recid);
      break;
    default:
      select();
      break;
  }
  if (isset($order)) $_SESSION["order"] = $order;
  if (isset($ordtype)) $_SESSION["type"] = $ordtype;
  if (isset($filter)) $_SESSION["filter"] = $filter;
  if (isset($filterfield)) $_SESSION["filter_field"] = $filterfield;
  if (isset($wholeonly)) $_SESSION["wholeonly"] = $wholeonly;
  mysqli_close($conn);
?>
<table class="bd" width="100%"><tr><td class="hr"> Convenios</td></tr></table>
</body>
</html>

<?php function select()
  {
  global $a;
  global $showrecs;
  global $page;
  global $filter;
  global $filterfield;
  global $wholeonly;
  global $order;
  global $ordtype;


  if ($a == "reset") {
    $filter = "";
    $filterfield = "";
    $wholeonly = "";
    $order = "";
    $ordtype = "";
  }

  $checkstr = "";
  if ($wholeonly) $checkstr = " checked";
  if ($ordtype == "asc") { $ordtypestr = "desc"; } else { $ordtypestr = "asc"; }
  $res = sql_select();
  $count = sql_getrecordcount();
  if ($count % $showrecs != 0) {
    $pagecount = intval($count / $showrecs) + 1;
  }
  else {
    $pagecount = intval($count / $showrecs);
  }
  $startrec = $showrecs * ($page - 1);
  if ($startrec < $count) {mysqli_data_seek($res, $startrec);}
  $reccount = min($showrecs * $page, $count);
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr><td></td></tr>
<tr><td>Total registros  <?php echo $startrec + 1 ?> - <?php echo $reccount ?> de <?php echo $count ?></td></tr>
</table>
<hr size="1" noshade>
<form action="convenios.php" method="post">
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><b>Criterios de búsqueda</b>&nbsp;</td>
<td><input type="text" name="filter" value="<?php echo $filter ?>"></td>
<td><select name="filter_field">
<option value="">Todos los campos</option>
<option value="<?php echo "Institucion" ?>"<?php if ($filterfield == "Institucion") { echo "selected"; } ?>><?php echo ("Institucion") ?></option>
<option value="<?php echo "codigo" ?>"<?php if ($filterfield == "codigo") { echo "selected"; } ?>><?php echo ("Codigo") ?></option>
<option value="<?php echo "objetivos" ?>"<?php if ($filterfield == "objetivos") { echo "selected"; } ?>><?php echo ("Objetivos") ?></option>
<option value="<?php echo "finicio" ?>"<?php if ($filterfield == "finicio") { echo "selected"; } ?>><?php echo ("Inicio") ?></option>
<option value="<?php echo "ffin" ?>"<?php if ($filterfield == "ffin") { echo "selected"; } ?>><?php echo ("Fin") ?></option>
<option value="<?php echo "facultad" ?>"<?php if ($filterfield == "facultad") { echo "selected"; } ?>><?php echo ("Facultad") ?></option>
<option value="<?php echo "pais" ?>"<?php if ($filterfield == "pais") { echo "selected"; } ?>><?php echo ("Pais") ?></option>
</select></td>
<td><input type="checkbox" name="wholeonly"<?php echo $checkstr ?>>Todas las palabras</td>
</td></tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" name="action" value="Buscar"></td>
<td><a href="convenios.php?a=reset">Nueva búsqueda</a></td>
</tr>
</table>
</form>
<hr size="1" noshade>
<?php showpagenav($page, $pagecount); ?>
<br>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
<tr>
<td class="hr">&nbsp;</td>
<td class="hr"><a class="hr" href="convenios.php?order=<?php echo "id" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Numero") ?></a></td>
<td class="hr"><a class="hr" href="convenios.php?order=<?php echo "Institucion" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Institucion") ?></a></td>
<td class="hr"><a class="hr" href="convenios.php?order=<?php echo "codigo" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Abreviacion") ?></a></td>
<td class="hr"><a class="hr" href="convenios.php?order=<?php echo "objetivos" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Objetivos") ?></a></td>
<td width=7%  class="hr"><a class="hr" href="convenios.php?order=<?php echo "finicio" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Inicio") ?></a></td>
<td width=7% class="hr"><a class="hr" href="convenios.php?order=<?php echo "ffin" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Fin") ?></a></td>
<td class="hr"><a class="hr" href="convenios.php?order=<?php echo "facultad" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Facultad") ?></a></td>
<td class="hr"><a class="hr" href="convenios.php?order=<?php echo "pais" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Pais") ?></a></td>
</tr>
<?php
  for ($i = $startrec; $i < $reccount; $i++)
  {
    $row = mysqli_fetch_assoc($res);
    $style = "dr";
    if ($i % 2 != 0) {
      $style = "sr";
    }
?>
<tr>
<?php /*<td class="<?php echo $style ?>"><a href="convenios.php?a=view&recid=<?php echo $i ?>">Ver</a></td>*/?>
<td class="<?php echo $style ?>"><a target=_blank href="documentos/convenios/<?php echo htmlspecialchars($row["id"]) ?>.pdf">Ver</a></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["id"]) ?></td>
<td class="<?php echo $style ?>"><?php echo ($row["Institucion"]) ?></td>
<td class="<?php echo $style ?>"><?php echo ($row["codigo"]) ?></td>
<td class="<?php echo $style ?>"><?php echo ($row["objetivos"]) ?></td>
<td class="<?php echo $style ?>"><?php echo (implode("/", array_reverse( preg_split("/\D/", $row["finicio"]) ) )) ?></td>
<td class="<?php echo $style ?>"><?php echo ($row["ffin"]<>'0000-00-00'?htmlspecialchars(implode("/", array_reverse( preg_split("/\D/", $row["ffin"]) ) )):"-") ?></td>
<td class="<?php echo $style ?>"><?php echo ($row["facultad"]) ?></td>
<td class="<?php echo $style ?>"><?php echo ($row["pais"]) ?></td>
</tr>
<?php
  }
  mysqli_free_result($res);
?>
</table>
<br>
<?php showpagenav($page, $pagecount); ?>
<?php } ?>

<?php function showrow($row, $recid)
  {
?>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
<tr>
<td class="hr"><?php echo htmlspecialchars("Instituci▒n")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["Institucion"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("C▒digo")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["codigo"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Objetivos")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["objetivos"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Inicio")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["finicio"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Fin")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["ffin"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Facultad")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["facultad"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Pa▒s")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["pais"]) ?></td>
</tr>
</table>
<?php } ?>

<?php function showroweditor($row, $iseditmode)
  {
  global $conn;
?>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
<tr>
<td class="hr"><?php echo htmlspecialchars("id")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="id" value="<?php echo str_replace('"', '&quot;', trim($row["id"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Institucion")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="Institucion" maxlength="200"><?php echo str_replace('"', '&quot;', trim($row["Institucion"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Codigo")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="codigo" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["codigo"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Objetivos")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="objetivos" maxlength="500"><?php echo str_replace('"', '&quot;', trim($row["objetivos"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Inicio")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="finicio" value="<?php echo str_replace('"', '&quot;', trim($row["finicio"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Fin")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="ffin" value="<?php echo str_replace('"', '&quot;', trim($row["ffin"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Facultad")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="facultad" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["facultad"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Pais")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="pais" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["pais"])) ?>"></td>
</tr>
</table>
<?php } ?>

<?php function showpagenav($page, $pagecount)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="convenios.php?a=add"></a>&nbsp;</td>
<?php if ($page > 1) { ?>
<td><a href="convenios.php?page=<?php echo $page - 1 ?>">&lt;&lt;&nbsp;Ant</a>&nbsp;</td>
<?php } ?>
<?php
  global $pagerange;

  if ($pagecount > 1) {

  if ($pagecount % $pagerange != 0) {
    $rangecount = intval($pagecount / $pagerange) + 1;
  }
  else {
    $rangecount = intval($pagecount / $pagerange);
  }
  for ($i = 1; $i < $rangecount + 1; $i++) {
    $startpage = (($i - 1) * $pagerange) + 1;
    $count = min($i * $pagerange, $pagecount);

    if ((($page >= $startpage) && ($page <= ($i * $pagerange)))) {
      for ($j = $startpage; $j < $count + 1; $j++) {
        if ($j == $page) {
?>
<td><b><?php echo $j ?></b></td>
<?php } else { ?>
<td><a href="convenios.php?page=<?php echo $j ?>"><?php echo $j ?></a></td>
<?php } } } else { ?>
<td><a href="convenios.php?page=<?php echo $startpage ?>"><?php echo $startpage ."..." .$count ?></a></td>
<?php } } } ?>
<?php if ($page < $pagecount) { ?>
<td>&nbsp;<a href="convenios.php?page=<?php echo $page + 1 ?>">Próx&nbsp;&gt;&gt;</a>&nbsp;</td>
<?php } ?>
</tr>
</table>
<?php } ?>

<?php function showrecnav($a, $recid, $count)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="convenios.php">Index Page</a></td>
<?php if ($recid > 0) { ?>
<td><a href="convenios.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>">Prior Record</a></td>
<?php } if ($recid < $count - 1) { ?>
<td><a href="convenios.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>">Next Record</a></td>
<?php } ?>
</tr>
</table>
<hr size="1" noshade>
<?php } ?>

<?php function addrec()
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="convenios.php">Index Page</a></td>
</tr>
</table>
<hr size="1" noshade>
<form enctype="multipart/form-data" action="convenios.php" method="post">
<p><input type="hidden" name="sql" value="insert"></p>
<?php
$row = array(
  "id" => "",
  "Institucion" => "",
  "codigo" => "",
  "objetivos" => "",
  "finicio" => "",
  "ffin" => "",
  "facultad" => "",
  "pais" => "");
showroweditor($row, false);
?>
<p><input type="submit" name="action" value="Post"></p>
</form>
<?php } ?>

<?php function viewrec($recid)
{
  $res = sql_select();
  $count = sql_getrecordcount();
  mysqli_data_seek($res, $recid);
  $row = mysqli_fetch_assoc($res);
  showrecnav("view", $recid, $count);
?>
<br>
<?php showrow($row, $recid) ?>
<br>
<hr size="1" noshade>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
//<tr>
//<td><a href="convenios.php?a=add">Add Record</a></td>
//<td><a href="convenios.php?a=edit&recid=<?php echo $recid ?>">Edit Record</a></td>
//</tr>
</table>
<?php
  mysqli_free_result($res);
} ?>

<?php function editrec($recid)
{
  $res = sql_select();
  $count = sql_getrecordcount();
  mysqli_data_seek($res, $recid);
  $row = mysqli_fetch_assoc($res);
  showrecnav("edit", $recid, $count);
?>
<br>
<form enctype="multipart/form-data" action="convenios.php" method="post">
<input type="hidden" name="sql" value="update">
<input type="hidden" name="xid" value="<?php echo $row["id"] ?>">
<?php showroweditor($row, true); ?>
<p><input type="submit" name="action" value="Post"></p>
</form>
<?php
  mysql_free_result($res);
} ?>

<?php 
function connect()
{
  $conn = mysqli_connect("127.0.0.1", "root", "");
  mysqli_select_db($conn,"dric_db");
  $conn -> set_charset("utf8");
  return $conn;
}

function sqlvalue($val, $quote)
{
  if ($quote)
    $tmp = sqlstr($val);
  else
    $tmp = $val;
  if ($tmp == "")
    $tmp = "NULL";
  elseif ($quote)
    $tmp = "'".$tmp."'";
  return $tmp;
}

function sqlstr($val)
{
  return str_replace("'", "''", $val);
}

function sql_select()
{
  global $conn;
  global $order;
  global $ordtype;
  global $filter;
  global $filterfield;
  global $wholeonly;

  $filterstr = sqlstr($filter);
  if (!$wholeonly && isset($wholeonly) && $filterstr!='') $filterstr = "%" .$filterstr ."%";
  $sql = "SELECT `id`, `Institucion`, `codigo`, `objetivos`, `finicio`, `ffin`, `facultad`, `pais` 
          FROM `convenios`";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`Institucion` like '" .$filterstr ."') or (`codigo` like '" .$filterstr ."') or (`objetivos` like '" .$filterstr ."') or (`finicio` like '" .$filterstr ."') or (`ffin` like '" .$filterstr ."') or (`facultad` like '" .$filterstr ."') or (`pais` like '" .$filterstr ."')";
  }
  if (isset($order) && $order!='') $sql .= " order by `" .sqlstr($order) ."`";
  if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  $res = mysqli_query($conn,$sql)or die($conn->error);
  return $res;
}

function sql_getrecordcount()
{
  global $conn;
  global $order;
  global $ordtype;
  global $filter;
  global $filterfield;
  global $wholeonly;

  $filterstr = sqlstr($filter);
  if (!$wholeonly && isset($wholeonly) && $filterstr!='') $filterstr = "%" .$filterstr ."%";
  $sql = "SELECT COUNT(*) FROM `convenios`";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`Institucion` like '" .$filterstr ."') or (`codigo` like '" .$filterstr ."') or (`objetivos` like '" .$filterstr ."') or (`finicio` like '" .$filterstr ."') or (`ffin` like '" .$filterstr ."') or (`facultad` like '" .$filterstr ."') or (`pais` like '" .$filterstr ."')";
  }
  $res = mysqli_query($conn,$sql) or die($conn->error);
  $row = mysqli_fetch_assoc($res);
  reset($row);
  return current($row);
}

function sql_insert()
{
  global $conn;
  global $_POST;

  $sql = "insert into `convenios` (`id`, `Institucion`, `codigo`, `objetivos`, `finicio`, `ffin`, `facultad`, `pais`) values (" .sqlvalue(@$_POST["id"], false).", " .sqlvalue(@$_POST["Institucion"], true).", " .sqlvalue(@$_POST["codigo"], true).", " .sqlvalue(@$_POST["objetivos"], true).", " .sqlvalue(@$_POST["finicio"], true).", " .sqlvalue(@$_POST["ffin"], true).", " .sqlvalue(@$_POST["facultad"], true).", " .sqlvalue(@$_POST["pais"], true).")";
  mysql_query($sql, $conn) or die(mysql_error());
}

function sql_update()
{
  global $conn;
  global $_POST;

  $sql = "update `convenios` set `id`=" .sqlvalue(@$_POST["id"], false).", `Institucion`=" .sqlvalue(@$_POST["Institucion"], true).", `codigo`=" .sqlvalue(@$_POST["codigo"], true).", `objetivos`=" .sqlvalue(@$_POST["objetivos"], true).", `finicio`=" .sqlvalue(@$_POST["finicio"], true).", `ffin`=" .sqlvalue(@$_POST["ffin"], true).", `facultad`=" .sqlvalue(@$_POST["facultad"], true).", `pais`=" .sqlvalue(@$_POST["pais"], true) ." where " .primarykeycondition();
  mysql_query($sql, $conn) or die(mysql_error());
}
function primarykeycondition()
{
  global $_POST;
  $pk = "";
  $pk .= "(`id`";
  if (@$_POST["xid"] == "") {
    $pk .= " IS NULL";
  }else{
  $pk .= " = " .sqlvalue(@$_POST["xid"], false);
  };
  $pk .= ")";
  return $pk;
}
 ?>

