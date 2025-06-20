<?php 
$tasks = [["id"=>1,"title"=>"Belajar PHP","status"=>"belum"],["id"=>2,"title"=>"Kerjakan Tugas UX","status"=>"selesai"]];
$id = '';
$status = '';
$title = '';

function perulangan_task($tasks){
	for ($i=0; $i < count($tasks) ; $i++) { 
		if($tasks[$i]["status"] == 'selesai'){
			$status = 'checked';
		}else{
			$status = '';
		}
		echo '
			<tr>
				<td>'.$tasks[$i]["id"].'</td>
				<td>'.$tasks[$i]["title"].'</td>
				<td>'.$tasks[$i]["status"].'</td>
				 <td>
                    <form method="post" style="margin:0;">
                        <input type="hidden" name="id" value="'. $tasks[$i]['id'] .'">
                        <input type="checkbox" name="status" onchange="this.form.submit()" '.$status.'>
                        <input type="hidden" name="update" value="1">
                    </form>
                </td>
                <td>
                    <form method="post" style="margin:0;">
                        <input type="hidden" name="id" value="'. $tasks[$i]['id'] .'">
                        <input type="hidden" name="status" value="'. $tasks[$i]['status'] .'">
                        <input type="hidden" name="title" value="'. $tasks[$i]['title'] .'">
                        <button type="submit" name="edit">Edit</button>
                        <button type="submit" name="delete" onclick="return confirm(\'Yakin ingin menghapus?\')">Hapus</button>
                    </form>
                </td>
			</tr>';

	}
}

// Fungsi untuk menambah data

function tambah_data($status,$title,$tasks){
	$maxId = max(array_column($tasks, 'id'))+1;
    $newTask = [
        'id' => $maxId,
        'title' => $title,
        'status' => $status
    ];
    
    // Tambahkan ke array tasks
    $tasks[] = $newTask;

    return $tasks;

}


// Fungsi untuk mengubah status
function update_status($id, $status, $tasks) {
    foreach ($tasks as &$task) {
        if ($task['id'] == $id) {
            $task['status'] = $status;
            break;
        }
    }
    return $tasks;
}

// Fungsi untuk edit data
function edit_data($id, $status,$title, $tasks) {
    foreach ($tasks as &$task) {
        if ($task['id'] == $id) {
            $task['status'] = $status;
            $task['title'] = $title;
            break;
        }
    }
    return $tasks;
}


// Fungsi hapus task
function delete_task($id, $tasks) {
    return array_values(array_filter($tasks, function ($task) use ($id) {
        return $task['id'] != $id;
    }));
}

// Tangani form checklist (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	//Fungsi untuk update status
    if (isset($_POST['update'])) {
        $id = (int)$_POST['id'];
        $status = isset($_POST['status']) ? 'selesai' : 'belum';
        $tasks = update_status($id, $status, $tasks);
    //Fungsi untuk hapus array
    } elseif (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        $tasks = delete_task($id, $tasks);
    //Fungsi untuk tambah array
    } elseif (isset($_POST['tambah'])) {
        $status = $_POST['status'];
        $title = $_POST['title'];
        $tasks = tambah_data($status, $title,$tasks);
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $title = $_POST['title'];
    }elseif(isset($_POST['submit_edit'])){
    	$id = $_POST['id'];
        $status = $_POST['status'];
        $title = $_POST['title'];
        $tasks = edit_data($id,$status, $title,$tasks);
    }
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>To Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
	<h1>Aplikasi To Do List</h1>
	<br>
	<div class="card">
		<div class="card-body">
		<h3>Form tambah tugas</h3>
		<form  method="post">
			<input type="hidden" name="id" value="<?= $id ?>">
		  <div class="form-group">
		    <label for="title">Title</label>
		    <input type="text" class="form-control" id="title" placeholder="Masukan Title" name="title" value="<?= $title ?>" required>
		  </div>
		  <div class="form-group">
		    <label for="status">Status</label>
		    <select name="status" class="form-control" id="status">
				<option value="belum">Belum Selesai</option>
				<option value="selesai">Selesai</option>
			</select>
		  </div>
		  <?php
		  if(isset($_POST['edit'])){
		  	echo '<button type="submit" name="submit_edit" class="btn btn-primary">Edit</button>';
		  }else{
		  	echo '<button type="submit" name="tambah" class="btn btn-primary">Submit</button>';
		  }
		  ?>
		  
		</form>

		</div>
	</div>
	<hr>
	<h3>Daftar Tugas</h3>
	<table class="table table-striped">
	  <thead>
			<tr>
				<th>ID</th>
				<th>TITLE</th>
				<th>STATUS</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo perulangan_task($tasks);
			?>
		</tbody>
	</table>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>