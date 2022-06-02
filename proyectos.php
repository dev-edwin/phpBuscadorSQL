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

<html>
<head>
<title>dric -- proyectos</title>
<meta name="generator" http-equiv="content-type" content="text/html; charset=utf-8">
<meta charset="utf8">
<style type="text/css">
  body {
    background-color: #FFFFFF;
    color: #004080;
    font-family: Arial;
    font-size: 12px;
  }
  .bd {
    background-color: #FFFFFF;
    color: #004080;
    font-family: Arial;
    font-size: 12px;
  }
  .tbl {
    background-color: #FFFFFF;
  }
  a:link {
    background-color: #FFFFFF01;
    color: #00008B;
    font-family: Arial;
    font-size: 12px;
  }
  a:active {
    background-color: #FFFFFF01;
    color: #191970;
    font-family: Arial;
    font-size: 12px;
  }
  a:visited {
    color: #191970;
    font-family: Arial;
    font-size: 12px;
  }
  .hr {
    background-color: #336699;
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  a.hr:link {
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  a.hr:active {
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  a.hr:visited {
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  .dr {
    background-color: #FFFFFF;
    color: #000000;
    font-family: Arial;
    font-size: 12px;
  }
  .sr {
    background-color: #FFFFCF;
    color: #000000;
    font-family: Arial;
    font-size: 12px;
  }
</style>
</head>
<body>
<table class="bd" width="100%"><tr><td class="hr"><h2>Proyectos de la DRIC</h2></td></tr></table>
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
  }

  switch ($a) {
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
<table class="bd" width="100%"><tr><td class="hr">Proyectos</td></tr></table>
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
<tr><td>Registros <?php echo $startrec + 1 ?> - <?php echo $reccount ?> de <?php echo $count ?></td></tr>
</table>
<hr size="1" noshade>
<form action="proyectos.php" method="post">
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><b>Criterios de búsqueda</b>&nbsp;</td>
<td><input type="text" name="filter" value="<?php echo $filter ?>"></td>
<td><select name="filter_field">
<option value="">Todos los campos</option>
<option value="<?php echo "abreviacion" ?>"<?php if ($filterfield == "abreviacion") { echo "selected"; } ?>><?php echo ("Abreviacion") ?></option>
<option value="<?php echo "nombre" ?>"<?php if ($filterfield == "nombre") { echo "selected"; } ?>><?php echo ("Nombre") ?></option>
<option value="<?php echo "facultad" ?>"<?php if ($filterfield == "facultad") { echo "selected"; } ?>><?php echo ("Facultad") ?></option>
<option value="<?php echo "unidad" ?>"<?php if ($filterfield == "unidad") { echo "selected"; } ?>><?php echo ("Unidad") ?></option>
<option value="<?php echo "objetivos" ?>"<?php if ($filterfield == "objetivos") { echo "selected"; } ?>><?php echo ("Objetivos") ?></option>
<option value="<?php echo "pais" ?>"<?php if ($filterfield == "pais") { echo "selected"; } ?>><?php echo ("País") ?></option>
<option value="<?php echo "agencia" ?>"<?php if ($filterfield == "agencia") { echo "selected"; } ?>><?php echo ("Agencia") ?></option>
<option value="<?php echo "finicio" ?>"<?php if ($filterfield == "finicio") { echo "selected"; } ?>><?php echo ("Inicio") ?></option>
<option value="<?php echo "ffin" ?>"<?php if ($filterfield == "ffin") { echo "selected"; } ?>><?php echo ("Fin") ?></option>
</select></td>
<td><input type="checkbox" name="wholeonly"<?php echo $checkstr ?>>Todas las palabras</td>
</td></tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" name="action" value="Buscar"></td>
<td><a href="proyectos.php?a=reset">Nueva búsqueda</a></td>
</tr>
</table>
</form>
<hr size="1" noshade>
<?php showpagenav($page, $pagecount); ?>
<br>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
<tr>
<td class="hr">&nbsp;</td>
<td class="hr"><a class="hr" href="proyectos.php?order=<?php echo "abreviacion" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Abreviacion") ?></a></td>
<td class="hr"><a class="hr" href="proyectos.php?order=<?php echo "nombre" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Nombre") ?></a></td>
<td class="hr"><a class="hr" href="proyectos.php?order=<?php echo "facultad" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Facultad") ?></a></td>
<td class="hr"><a class="hr" href="proyectos.php?order=<?php echo "unidad" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Unidad") ?></a></td>
<td width=80% class="hr"><a class="hr" href="proyectos.php?order=<?php echo "objetivos" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Objetivos") ?></a></td>
<td class="hr"><a class="hr" href="proyectos.php?order=<?php echo "pais" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Pais") ?></a></td>
<td class="hr"><a class="hr" href="proyectos.php?order=<?php echo "agencia" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Agencia") ?></a></td>
<td class="hr"><a class="hr" href="proyectos.php?order=<?php echo "finicio" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Inicio") ?></a></td>
<td class="hr"><a class="hr" href="proyectos.php?order=<?php echo "ffin" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("Fin") ?></a></td>
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
<td class="<?php echo $style ?>"><a href="proyectos.php?a=view&recid=<?php echo $i ?>">Ver</a></td>
<td class="<?php echo $style ?>"><?php echo ($row["abreviacion"]) ?></td>
<td class="<?php echo $style ?>"><?php echo ($row["nombre"]) ?></td>
<td class="<?php echo $style ?>"><?php echo ($row["facultad"]) ?></td>
<td class="<?php echo $style ?>"><?php echo ($row["unidad"]) ?></td>
<td class="<?php echo $style ?>"><?php echo (($row["objetivos"])) ?></td>
<td class="<?php echo $style ?>"><?php echo ($row["pais"]) ?></td>
<td class="<?php echo $style ?>"><?php echo ($row["agencia"]) ?></td>
<td class="<?php echo $style ?>"><?php echo (implode("/", array_reverse( preg_split("/\D/", $row["finicio"]) ) )) ?></td>
<td class="<?php echo $style ?>"><?php echo (implode("/", array_reverse( preg_split("/\D/", $row["ffin"]) ) )) ?></td>
</tr>
<?php
  }
  mysql_free_result($res);
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
<td class="hr"><?php echo ("Abreviación")."&nbsp;" ?></td>
<td class="dr"><?php echo ($row["abreviacion"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo ("Nombre")."&nbsp;" ?></td>
<td class="dr"><?php echo ($row["nombre"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo ("Facultad")."&nbsp;" ?></td>
<td class="dr"><?php echo ($row["facultad"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo ("Unidad")."&nbsp;" ?></td>
<td class="dr"><?php echo ($row["unidad"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo ("Objetivos")."&nbsp;" ?></td>
<td class="dr"><?php echo ($row["objetivos"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo ("País")."&nbsp;" ?></td>
<td class="dr"><?php echo ($row["pais"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo ("Agencia")."&nbsp;" ?></td>
<td class="dr"><?php echo ($row["agencia"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo ("Inicio")."&nbsp;" ?></td>
<td class="dr"><?php echo ($row["finicio"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo ("Fin")."&nbsp;" ?></td>
<td class="dr"><?php echo ($row["ffin"]) ?></td>
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
<td class="hr"><?php echo htmlspecialchars("Abreviaci▒n")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="abreviacion" maxlength="50" value="<?php echo str_replace('"', '&quot;', trim($row["abreviacion"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Nombre")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="nombre" maxlength="200"><?php echo str_replace('"', '&quot;', trim($row["nombre"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Facultad")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="facultad" maxlength="15" value="<?php echo str_replace('"', '&quot;', trim($row["facultad"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Unidad")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="unidad" maxlength="50" value="<?php echo str_replace('"', '&quot;', trim($row["unidad"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Objetivos")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="objetivos" maxlength="550"><?php echo str_replace('"', '&quot;', trim($row["objetivos"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Pa▒s")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="pais" maxlength="30" value="<?php echo str_replace('"', '&quot;', trim($row["pais"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Agencia")."&nbsp;" ?></td>
<td class="dr"><textarea cols="35" rows="4" name="agencia" maxlength="100"><?php echo str_replace('"', '&quot;', trim($row["agencia"])) ?></textarea></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Inicio")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="finicio" value="<?php echo str_replace('"', '&quot;', trim($row["finicio"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("Fin")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="ffin" value="<?php echo str_replace('"', '&quot;', trim($row["ffin"])) ?>"></td>
</tr>
</table>
<?php } ?>

<?php function showpagenav($page, $pagecount)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="proyectos.php?a=add"></a>&nbsp;</td>
<?php if ($page > 1) { ?>
<td><a href="proyectos.php?page=<?php echo $page - 1 ?>">&lt;&lt;&nbsp;Prev</a>&nbsp;</td>
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
<td><a href="proyectos.php?page=<?php echo $j ?>"><?php echo $j ?></a></td>
<?php } } } else { ?>
<td><a href="proyectos.php?page=<?php echo $startpage ?>"><?php echo $startpage ."..." .$count ?></a></td>
<?php } } } ?>
<?php if ($page < $pagecount) { ?>
<td>&nbsp;<a href="proyectos.php?page=<?php echo $page + 1 ?>">Next&nbsp;&gt;&gt;</a>&nbsp;</td>
<?php } ?>
</tr>
</table>
<?php } ?>

<?php function showrecnav($a, $recid, $count)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="proyectos.php">Proyectos</a></td>
<?php if ($recid > 0) { ?>
<td><a href="proyectos.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>">Anterior</a></td>
<?php } if ($recid < $count - 1) { ?>
<td><a href="proyectos.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>">Siguiente</a></td>
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
<td><a href="proyectos.php">Index Page</a></td>
</tr>
</table>
<hr size="1" noshade>
<form enctype="multipart/form-data" action="proyectos.php" method="post">
<p><input type="hidden" name="sql" value="insert"></p>
<?php
$row = array(
  "id" => "",
  "abreviacion" => "",
  "nombre" => "",
  "facultad" => "",
  "unidad" => "",
  "objetivos" => "",
  "pais" => "",
  "agencia" => "",
  "finicio" => "",
  "ffin" => "");
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
<tr>
<td><a href="proyectos.php?a=add"></a></td>
<td><a href="proyectos.php?a=edit&recid=<?php echo $recid ?>"></a></td>
</tr>
</table>
<?php
  mysql_free_result($res);
} ?>

<?php function editrec($recid)
{
  $res = sql_select();
  $count = sql_getrecordcount();
  mysql_data_seek($res, $recid);
  $row = mysql_fetch_assoc($res);
  showrecnav("edit", $recid, $count);
?>
<br>
<form enctype="multipart/form-data" action="proyectos.php" method="post">
<input type="hidden" name="sql" value="update">
<input type="hidden" name="xid" value="<?php echo $row["id"] ?>">
<?php showroweditor($row, true); ?>
<p><input type="submit" name="action" value="Post"></p>
</form>
<?php
  mysql_free_result($res);
} ?>

<?php function connect()
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
  $sql = "SELECT `id`, `abreviacion`, `nombre`, `facultad`, `unidad`, `objetivos`, `pais`, `agencia`, `finicio`, `ffin` FROM `proyectos`";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`abreviacion` like '" .$filterstr ."') or (`nombre` like '" .$filterstr ."') or (`facultad` like '" .$filterstr ."') or (`unidad` like '" .$filterstr ."') or (`objetivos` like '" .$filterstr ."') or (`pais` like '" .$filterstr ."') or (`agencia` like '" .$filterstr ."') or (`finicio` like '" .$filterstr ."') or (`ffin` like '" .$filterstr ."')";
  }
  if (isset($order) && $order!='') $sql .= " order by `" .sqlstr($order) ."`";
  if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  $res = mysqli_query($conn,$sql) or die($conn->connect_error);
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
  $sql = "SELECT COUNT(*) FROM `proyectos`";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`abreviacion` like '" .$filterstr ."') or (`nombre` like '" .$filterstr ."') or (`facultad` like '" .$filterstr ."') or (`unidad` like '" .$filterstr ."') or (`objetivos` like '" .$filterstr ."') or (`pais` like '" .$filterstr ."') or (`agencia` like '" .$filterstr ."') or (`finicio` like '" .$filterstr ."') or (`ffin` like '" .$filterstr ."')";
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

  $sql = "insert into `proyectos` (`id`, `abreviacion`, `nombre`, `facultad`, `unidad`, `objetivos`, `pais`, `agencia`, `finicio`, `ffin`) values (" .sqlvalue(@$_POST["id"], false).", " .sqlvalue(@$_POST["abreviacion"], true).", " .sqlvalue(@$_POST["nombre"], true).", " .sqlvalue(@$_POST["facultad"], true).", " .sqlvalue(@$_POST["unidad"], true).", " .sqlvalue(@$_POST["objetivos"], true).", " .sqlvalue(@$_POST["pais"], true).", " .sqlvalue(@$_POST["agencia"], true).", " .sqlvalue(@$_POST["finicio"], true).", " .sqlvalue(@$_POST["ffin"], true).")";
  mysqli_query($conn,$sql) or die($conn->error);
}

function sql_update()
{
  global $conn;
  global $_POST;

  $sql = "update `proyectos` set `id`=" .sqlvalue(@$_POST["id"], false).", `abreviacion`=" .sqlvalue(@$_POST["abreviacion"], true).", `nombre`=" .sqlvalue(@$_POST["nombre"], true).", `facultad`=" .sqlvalue(@$_POST["facultad"], true).", `unidad`=" .sqlvalue(@$_POST["unidad"], true).", `objetivos`=" .sqlvalue(@$_POST["objetivos"], true).", `pais`=" .sqlvalue(@$_POST["pais"], true).", `agencia`=" .sqlvalue(@$_POST["agencia"], true).", `finicio`=" .sqlvalue(@$_POST["finicio"], true).", `ffin`=" .sqlvalue(@$_POST["ffin"], true) ." where " .primarykeycondition();
  mysqli_query($conn,$sql) or die($conn->error);
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

