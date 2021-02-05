<?php
  session_start();
	include "../conexion.php";
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include 'includes/scripts.php'; ?>
	<title>Lista de clientes</title>
</head>
<body>
	<?php include 'includes/header.php'; ?>
	<section id="container">
		<?php
			$busqueda = strtolower($_REQUEST['busqueda']);
			if (empty($busqueda)) {
				header("location: lista_clientes.php");
				mysqli_close($conexion);
			}
		 ?>

		<h1>lista de clientes</h1>
		<a href="registro_cliente.php" class="btn_new">Crear cliente</a>

		<form action="buscar_cliente.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda"  placeholder="buscar" value="<?php echo $busqueda; ?>">
			<input type="submit" value="buscar" class="btn_search">
		</form>

		<table>
				<tr>
					<th>ID</th>
					<th>NIT</th>
					<th>NOMBRE</th>
					<th>TELÉFONO</th>
					<th>DIRECCIÓN</th>
					<th>ACCIONES</th>
				</tr>

				<?php
				// paginador

				  $sql_registe = mysqli_query($conexion,"SELECT COUNT(*) as total_registro FROM cliente
																													  	WHERE(idcliente like '%$busqueda%' or
																																		nit like '%$busqueda%' or
																																		nombre like '%$busqueda%' or
																																		telefono like '%$busqueda%' or
																																		direccion like '%$busqueda%'
																															      ) and estatus = 1");
				 	$result_register = mysqli_fetch_array($sql_registe);
					$total_registro = $result_register['total_registro'];

					$por_pagina = 10;

					if (empty($_GET['pagina'])){
						$pagina = 1;
					}else{
						$pagina = $_GET['pagina'];
					}

					$desde = ($pagina - 1) * $por_pagina;
					$total_paginas = ceil($total_registro / $por_pagina);


					$query = mysqli_query($conexion,"SELECT * FROM cliente WHERE
																											 (idcliente like '%$busqueda%' or
																												nit like '%$busqueda%' or
																												nombre like '%$busqueda%' or
																												telefono like '%$busqueda%' or
																								      	direccion like '%$busqueda%') and
																									      estatus=1	ORDER BY idcliente ASC limit $desde,$por_pagina");
					mysqli_close($conexion);
					$result = mysqli_num_rows($query);

					if ($result > 0) {

						while ($data = mysqli_fetch_array($query)) {
				?>
				<tr>
					<td><?php echo $data["idcliente"]; ?></td>
					<td><?php echo $data["nit"]; ?></td>
					<td><?php echo $data["nombre"]; ?></td>
					<td><?php echo $data["telefono"]; ?></td>
					<td><?php echo $data["direccion"]; ?></td>
					<td>
						<a class="link_edit" href="editar_cliente.php?id=<?php echo $data["idcliente"]; ?>">editar</a>

						<?php if ($_SESSION['rol'] == 4 || $_SESSION['rol'] == 5) { ?>
						|
						<a class="link_delete" href="eliminar_confirmar_cliente.php?id=<?php echo $data["idcliente"]; ?>">eliminar</a>
					<?php } ?>
					</td>
				</tr>
	<?php
			}
		}
	 ?>
		</table>
		<?php
			if ($total_registro != 0) {
		 ?>
		<div class="paginador">
			<ul>
				<?php
					if ($pagina !=1) {
				 ?>
					<li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|<</a></li>
					<li><a href="?pagina=<?php echo $pagina-1; ?>&busqueda=<?php echo $busqueda; ?>"><<</a></li>
				<?php
			   }
					for ($i=1; $i <= $total_paginas ; $i++) {
						if ($i == $pagina) {
							echo '<li class="pageSelected">'.$i.'</li>';
						}else {
							echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
						}
					}
					if ($pagina != $total_paginas) {
				 ?>
				<li><a href="?pagina=<?php echo $pagina+1; ?>&busqueda=<?php echo $busqueda; ?>">>></a></li>
				<li><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?>">>|</a></li>
			<?php } ?>
			</ul>
		</div>
		<?php } ?>
	</section>
	<?php include 'includes/footer.php'; ?>
</body>
</html>
