<html>
<head>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<style>

/* required style*/ 
.none{display: none;}
body {
    background-color: lightblue;
}

/* optional styles */
table tr th, table tr td{font-size: 1.2rem;}
.row{ margin:20px 20px 20px 20px;width: 100%;}
.glyphicon{font-size: 20px;}
.glyphicon-plus{float: right;}
a.glyphicon{text-decoration: none;}
a.glyphicon-trash{margin-left: 10px;}

</style>
<title>User</title>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="panel panel-default users-content">
            <div class="panel-heading">Users <a href="javascript:void(0);" class="glyphicon glyphicon-plus" id="addLink" onclick="javascript:$('#addForm').slideToggle();">Add</a></div>
            <div class="panel-body none formData" id="addForm">
                <h2 id="actionLabel">Add User</h2>
                <form class="form" id="userForm">
                    <div class="form-group">
                        <label>User Name</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <label>User Email</label>
                        <input type="text" class="form-control" name="email" id="email"/>
                    </div>
                    <div class="form-group">
                        <label>User Role</label>
                       <select class="form-control" style="width: 100%;" name="role" id="role">
                  <option value="Admin">Admin</option>
				  <option value="Super Admin">Super Admin</option>
                  <option value="User">User</option>
			     <option value="Client">Client</option>
                  
			  </select>
                    </div>
					<div class="form-group">
                        <label>User status</label>
                       <select class="form-control" style="width: 100%;" name="status" id="status">
                  <option value="Active">Active</option>
				  <option value="In Active">In Active</option>
                 
			  </select>
                    </div>
					  
                    <a href="javascript:void(0);" class="btn btn-warning" onclick="$('#addForm').slideUp();">Cancel</a>
                    <a href="javascript:void(0);" class="btn btn-success" onclick="userAction('add')">Add User</a>
                </form>
            </div>
            <div class="panel-body none formData" id="editForm">
                <h2 id="actionLabel">Edit User</h2>
                <form class="form" id="userForm">
                    <div class="form-group">
                        <label>User Name</label>
                        <input type="text" class="form-control" name="name" id="nameEdit"/>
                    </div>
                    <div class="form-group">
                        <label>User Email</label>
                        <input type="text" class="form-control" name="email" id="emailEdit"/>
                    </div>
                    <div class="form-group">
                        <label>User Role</label>

                       <select class="form-control" style="width: 100%;"  name="role" id="roleEdit">
                  <option value="Admin">Admin</option>
				  <option value="Super Admin">Super Admin</option>
                  <option value="User">User</option>
			     <option value="Client">Client</option>
                  
			  </select>
                    </div>
					<div class="form-group">
                        <label>User status</label>
                       <select class="form-control" style="width: 100%;" name="status" id="statusEdit">
                  <option value="Active">Active</option>
				  <option value="In Active">In Active</option>
                 
			  </select>
                    </div>
                    <input type="hidden" class="form-control" name="id" id="idEdit"/>
                    <a href="javascript:void(0);" class="btn btn-warning" onclick="$('#editForm').slideUp();">Cancel</a>
                    <a href="javascript:void(0);" class="btn btn-success" onclick="userAction('edit')">Update User</a>
                </form>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>User Name</th>
                        <th>User Email</th>
                        <th>User Role</th>
						<th>User status</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody id="userData">
                    <?php
                        include 'x_DB.php';
                        $db = new DB();
                        $users = $db->getRows('users',array('order_by'=>'id '));
                        if(!empty($users)): $count = 0; foreach($users as $user): $count++;
                    ?>
                    <tr>
                        <td><?php echo '#'.$count; ?></td>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['role']; ?></td>
						<td><?php echo $user['status']; ?></td>
                        <td>
                            <a href="javascript:void(0);" class="glyphicon glyphicon-edit" onclick="editUser('<?php echo $user['id']; ?>')"></a>
                            <a href="javascript:void(0);" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure to delete data?')?userAction('delete','<?php echo $user['id']; ?>'):false;"></a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="6">No user(s) found......</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function getUsers(){
    $.ajax({
        type: 'POST',
        url: 'x_userAction.php',
        data: 'action_type=view&'+$("#userForm").serialize(),
        success:function(html){
            $('#userData').html(html);
        }
    });
}
function userAction(type,id){
    id = (typeof id == "undefined")?'':id;
    var statusArr = {add:"added",edit:"updated",delete:"deleted"};
    var userData = '';
    if (type == 'add') {
        userData = $("#addForm").find('.form').serialize()+'&action_type='+type+'&id='+id;
    }else if (type == 'edit'){
        userData = $("#editForm").find('.form').serialize()+'&action_type='+type;
    }else{
        userData = 'action_type='+type+'&id='+id;
    }
	console.log (userData);
    $.ajax({
        type: 'POST',
        url: 'x_userAction.php',
        data: userData,
        success:function(msg){
            if(msg == 'ok'){
                alert('User data has been '+statusArr[type]+' successfully.');
                getUsers();
                $('.form')[0].reset();
                $('.formData').slideUp();
            }else{
                alert('Some problem occurred, please try again.');
            }
        }
    });
}


function editUser(id){
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'x_userAction.php',
        data: 'action_type=data&id='+id,
        success:function(data){
            $('#idEdit').val(data.id);
            $('#nameEdit').val(data.name);
            $('#emailEdit').val(data.email);
            $('#roleEdit').val(data.role);
			 $('#statusEdit').val(data.status);
            $('#editForm').slideDown();
        }
    });
}

</script>
</body>
</html>