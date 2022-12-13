<form action="" method="post" enctype="multipart/form-data" class="form_1">
	Изображение 2-го слайда:
	<input type="file" name="uploadfile_1">
	<br><br>
	
	Изображение 3-го слайда:
	<input type="file" name="uploadfile_2"><br><br>
	
	Изображение 4-го слайда:
	<input type="file" name="uploadfile_3">
	<br><br>
	
	Изображение 5-го слайда:
	<input type="file" name="uploadfile_4">
	<br><br>
	
	<input type="submit" id="btn" name="btn" value="Загрузить"><br>
</form>

<?

	$ftp_server = 'dashboard.imas.kz';
	$ftp_user_name = 'v-40047';
	$ftp_user_pass ='O0Hmt0f8';
	$name_file_1 = $_FILES['uploadfile_1']['name'];
	$name_file_1 = substr($name_file_1, -4);
	
	$name_file_2 = $_FILES['uploadfile_2']['name'];
	$name_file_2 = substr($name_file_2, -4);
	
	$name_file_3 = $_FILES['uploadfile_3']['name'];
	$name_file_3 = substr($name_file_3, -4);
	
	$name_file_4 = $_FILES['uploadfile_4']['name'];
	$name_file_4 = substr($name_file_4, -4);
	
	if($name_file_1 == '.jpg' || $name_file_1 == 'jpeg' || $name_file_1 == '.png' || 
		$name_file_2 == '.jpg' || $name_file_2 == 'jpeg' || $name_file_2 == '.png' ||
		$name_file_3 == '.jpg' || $name_file_3 == 'jpeg' || $name_file_3 == '.png' ||
		$name_file_4 == '.jpg' || $name_file_4 == 'jpeg' || $name_file_4 == '.png'){
		$new_file_1 = 'one.png';
		$my_file_1 = $_FILES['uploadfile_1']['tmp_name'];
		
		$new_file_2 = 'two.png';
		$my_file_2 = $_FILES['uploadfile_2']['tmp_name'];
		
		$new_file_3 = 'three.png';
		$my_file_3 = $_FILES['uploadfile_3']['tmp_name'];
		
		$new_file_4 = 'four.png';
		$my_file_4 = $_FILES['uploadfile_4']['tmp_name'];
		
		// echo $new_file_1.'==>'.$my_file_1.'<br>';
		// echo $new_file_2.'==>'.$my_file_2.'<br>';
		
		$conn_id = ftp_connect($ftp_server) or die("Не удалось установить соединение с $ftp_server"); 
		if(@ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)){
			// echo "Произведен вход";
			ftp_chdir($conn_id, '/www/dashboard.imas.kz/images');
			if($my_file_1 != ''){
				$upload_1 = ftp_put($conn_id, $new_file_1, $my_file_1, FTP_BINARY);
			}
			if($my_file_2 != ''){
				$upload_2 = ftp_put($conn_id, $new_file_2, $my_file_2, FTP_BINARY);
			}
			
			if($my_file_3 != ''){
				$upload_3 = ftp_put($conn_id, $new_file_3, $my_file_3, FTP_BINARY);
			}
			
			if($my_file_4 != ''){
				$upload_4 = ftp_put($conn_id, $new_file_4, $my_file_4, FTP_BINARY);
			}
			
			if($upload_1 || $upload_2){
				$file_upload = 'true';
				echo 'Файл загружен успешно<br>';
				if(ftp_chmod($conn_id, 0777, $new_file_1) !== false || ftp_chmod($conn_id, 0777, $new_file_2) !== false || ftp_chmod($conn_id, 0777, $new_file_3) !== false || ftp_chmod($conn_id, 0777, $new_file_4) !== false){
					// echo "Права изменены на 777";
				}else{
					// echo "Не удалось изменить права<br>";
				}
			}else{
				echo 'Ошибка при загрузке файла';
			}
		}else{
			echo "Ошибка на стороне сервера! Обратитесь к разработчикам";
		}
		ftp_close($conn_id);
	}
?>