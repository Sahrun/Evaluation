<?php
$SERVER = $_SERVER['DOCUMENT_ROOT'];
include $SERVER."/Evaluation/Config/ConfigHead.php";
?>
<div class="main-panel" id="User">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
          <h2 class="card-title"><strong>Daftar User</strong></h2>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>
                      <strong> No </strong>
                    </th>
                    <th>
                      <strong> Nama Lengkap </strong>
                    </th>
                    <th>
                      <strong> Group </strong>
                    </th>
                    <th>
                      <strong> Department </strong>
                    </th>
                    <th>
                      <strong> Status </strong>
                    </th>
                    <th>
                      <strong>Action</strong>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item,index) in Users">
                    <td>{{index + 1}}</td>
                    <td>{{item.FullName}}</td>
                    <td>{{item.GroupName}}</td>
                    <td>{{item.Department}}</td>
                    <td>{{item.Status}}</td>
                    <td><button v-on:click="Edit(item.UserId)" class="btn btn-primary mr-2">Edit</button></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="row">
              <button class="btn btn-success mr-2" style="margin-top: 30px;" v-on:click="NewUser">Tambah User</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- The Modal -->
  <div class="modal fade" id="UserModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Form Tambah User Baru</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <form class="forms-sample">
                 <div class="form-group">
                    <label for="FullName"><strong> Nama Lengkap  </strong></label>
                    <input type="text" class="form-control col-lg-4" id="FullName" placeholder="Full Name"
                    v-model.trim="User.FullName">
                     <span v-show="Submitted && !$v.User.FullName.required" class="text-danger"> Field harus diisi </span>
                  </div>
                  <div class="form-group">
                     <label for="FullName"><strong> Department </strong></label>
                     <input type="text" class="form-control col-lg-4" id="Department" placeholder="Department"
                     v-model.trim="User.Department">
                      <span v-show="Submitted && !$v.User.Department.required" class="text-danger"> Field harus diisi </span>
                  </div>
                  <div class="form-group">
                     <label for="Group"><strong> Group </strong></label>
                     <select v-model.trim="User.GroupId" class="form-control col-lg-4">
                       <option v-for="i in Groups" v-bind:value="i.GroupId">{{i.GroupName}}</option>
                     </select>
                      <span v-show="Submitted && !$v.User.GroupId.required" class="text-danger"> Field harus diisi </span>
                  </div>
                  <div class="form-group">
                    <label for="UserName"><strong> User Name </strong></label>
                    <input type="text" class="form-control col-lg-4" id="UserName" placeholder="User Name"
                    v-model.trim="User.UserName">
                     <span v-show="Submitted && !$v.User.UserName.required" class="text-danger"> Field harus diisi </span>
                  </div>
                  <div class="form-group" v-show="User.UserId">
                    <label for="Status"><strong>Status</strong></label>
                    <div class="form-radio">
                      <label class="form-check-label">
                        <input type="radio"
                        class="form-check-input"
                        v-model.trim="User.Status" id="Status"
                        value="Active" checked> Active
                      </label>
                    </div>
                    <div class="form-radio">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input"
                        v-model.trim="User.Status"
                        id="Status" value="Non-Active"> Non - Active
                      </label>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" value="SaveUoM" class="btn btn-success mr-2" v-on:click="Submit">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <?php include $SERVER."/Evaluation/Config/ConfigFooter.php"; ?>
  <script src="../../js/App/User.js"></script>
