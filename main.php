<?php
$servername = "";
$database = "";
$username = "";
$password = "";

//Connection
$conn = mysqli_connect($servername, $username, $password, $database);
//Check connection
if (!$conn) {
    die("Conexao falhou: " . mysqli_connect_error());
}


//mysqli_set_charset($obj_mysqli, 'utf8');

//Inserção do codigo
$Id_entrada    = -1;
$Nome   = "";
$Empresa  = "";
$Data = "";
$Autorizador     = "";


//Validando Id_entrada  a existência dos dados
if(isset($_POST["Nome"]) && isset($_POST["Empresa"]) && isset($_POST["Data"]) && isset($_POST["Autorizador"]))
{
		if(empty($_POST["Nome"]))
			$erro = "Campo nome obrigatório";
		elseif(empty($_POST["Empresa"]))
			$erro = "Campo empresa obrigatório";
		elseif(empty($_POST["Data"]))
			$erro = "Campo data obrigatório";
		elseif(empty($_POST["Autorizador"]))
			$erro = "Campo Autorizador obrigatório";
			

      //Altera aqui também.
		//Agora, o $Id_entrada, pode vir com o valor -1, que nos indica novo registro, 
		//ou, vir com um valor diferente de -1, ou seja, 
                //o código do registro no banco, que nos indica alteração dos dados.
		$Id_entrada     = $_POST["Id_entrada"];		
		$Nome   = $_POST["Nome"];
		$Empresa  = $_POST["Empresa"];
		$Data = $_POST["Data"];
		$Autorizador   = $_POST["Autorizador"];


 //Se o Id_entrada for -1, realizar o cadastro ou alteração dos dados enviados.
		if($Id_entrada == -1)
		{
			$stmt = $obj_mysqli->prepare("INSERT INTO 'entrada_visitantes' ('Nome','Empresa','Data','Autorizador') VALUES (?,?,?,?)");
			$stmt->bind_param('ssss', $Nome, $Empresa, $Data, $Autorizador);	
		
			if(!$stmt->execute())
			{
				$erro = $stmt->error;
			}
			else
			{
				header("Location:main.php");
				exit;
			}
		}

 //se não, vamos realizar a alteraçao dos dados,
 //porém, vamos nos certificar que o valor passado no $Id_entrada, seja válId_entradao para nosso caso.
		
		if(is_numeric($Id_entrada) && $Id_entrada >= 1)
		{
			$stmt = $obj_mysqli->prepare("UPDATE 'entrada_visitantes' SET 'Nome'=?, 'Empresa'=?, 'Data'=?, 'Autorizador'=? WHERE Id_entrada = ? ");
			$stmt->bind_param('ssssi', $Nome, $Empresa,$Data,$Autorizador, $Id_entrada);
		
			if(!$stmt->execute())
			{
				$erro = $stmt->error;
			}
			else
			{
				header("Location:main.php");
				exit;
			}
		}
		//retorna um erro.
		else
		{
			$erro = "Número inválido ";
		}
	}
												
else
//Incluimos este bloco, onde vamos verificar a existência do id passado...
if(isset($_GET["Id_entrada"]) && is_numeric($_GET["Id_entrada"]))
{
        //..,pegamos aqui o Id passado...
	$Id_entrada = (int)$_GET["Id_entrada"];
	
        //...montamos a consulta que será realizada....
	$stmt = $obj_mysqli->prepare("DELETE FROM 'entrada_visitantes' WHERE Id_entrada = ?");//
        //passamos o id como parâmetro, do tipo i = int, inteiro...
	$stmt->bind_param('i', $Id_entrada);
        //...mandamos executar a consulta...
	$stmt->execute();

header("Location:main.php");
		exit;
	}
	else
	{
		$stmt = $obj_mysqli->prepare("SELECT * FROM 'entrada_visitantes' WHERE Id_entrada = ?");
		$stmt->bind_param('i', $Id_entrada);
		$stmt->execute();
		
		$result = $stmt->get_result();
		$aux_query = $result->fetch_assoc();
	//...onde aqui, nós atribuímos às variáveis.
	$Nome = $aux_query["Nome"];
	$Empresa = $aux_query["Empresa"];
	$Data = $aux_query["Data"];
	$Autorizador = $aux_query["Autorizador"];

	/*Record(SAVE)
	$sql = "INSERT INTO entrada_visitantes (Nome, Empresa,Data, Autorizador) 
	VALUES = isset($_POST ['','','',''] )";
	if(mysqli_query($link, $sql)){
	echo “Gravação_bem_sucedida $sql.”;
	} else{
	echo “ERROR: Não foi capaz de executar, $sql. ” . mysqli_error($link);
	}

	//Delete
	$del = "DELETE FROM entrada_visitantes WHERE id_entrada = ""; ";

	$delgo = mysql_query($del) or die ('Erro ao deletar');
	echo "Deletado";
*/
		
/*
while ($aux_query = $result->fetch_assoc()) 
{
  echo '<tr>';
  echo '  <td>'.$aux_query["Id_entrada"].'</td>';
  echo '  <td>'.$aux_query["Nome"].'</td>';
  echo '  <td>'.$aux_query["Data"].'</td>';
  echo '  <td>'.$aux_query["Empresa"].'</td>';
  echo '  <td>'.$aux_query["Autorizador"].'</td>';
  echo '  <td><a href="'.$_SERVER["PHP_SELF"].'?Id_entrada='.$aux_query["Id_entrada"].'">Editar</a></td>';
  echo '  <td><a href="'.$_SERVER["PHP_SELF"].'?Id_entrada='.$aux_query["Id_entrada"].'&del=true">Excluir</a></td>';
  echo '</tr>';
}
*/

}
//Close connection
	mysqli_close($conn);
?>

