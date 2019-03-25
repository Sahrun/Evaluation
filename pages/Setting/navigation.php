<?php
$SERVER = $_SERVER['DOCUMENT_ROOT'];
include $SERVER."/Evaluation/Config/ConfigHead.php";
?>
<div class="main-panel" id="navigationSetting">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h2 class="card-title"><strong>Daftar Navigation</strong></h2>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>
                      <strong> No </strong>
                    </th>
                    <th>
                      <strong> Nama Navigation </strong>
                    </th>
                    <th>
                      <strong> Url </strong>
                    </th>
                    <th>
                      <strong> Group </strong>
                    </th>
                    <th>
                      <strong> Icon </strong>
                    </th>  
                    <th>
                      <strong> Order </strong>
                    </th>   
                    <th>
                      <strong>Action</strong>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(nav,index) in Navigations">
                    <td>{{index + 1}}</td>
                    <td>{{nav.NavigationName}}</td>
                    <td>{{nav.Url}}</td>
                    <td>{{nav.GroupName}}</td>
                    <td>{{nav.Icon}}</td>
                    <td>{{nav.Order}}</td>
                    <td><button v-on:click="Edit(nav.NavigationId)" class="btn btn-primary mr-2">Edit</button></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="row">
              <button class="btn btn-success mr-2" style="margin-top: 30px;" v-on:click="NewNavigation">Tambah Navigation</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- The Modal -->
  <div class="modal fade" id="NavigationModal" data-keyboard="false" data-backdrop="static"> -->
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" v-show="!Navigation.NavigationId">Form Tambah Navigation Baru</h4>
          <h4 class="modal-title" v-show="Navigation.NavigationId">Edit Navigation</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <form class="forms-sample">
                  <div class="form-group">
                    <label for="NavigationName"><strong> Nama Navigation </strong></label>
                    <input type="text" class="form-control col-lg-4" id="NavigationNamer" placeholder="Nama Navigation"
                    v-model.trim="Navigation.NavigationName">
                     <span v-show="Submitted && !$v.Navigation.NavigationName.required" class="text-danger"> Field harus diisi </span>
                  </div>
                  <div class="form-group">
                    <label for="Url"><strong> Url </strong></label>
                    <input type="text" class="form-control col-lg-4" id="Url" placeholder="Url"
                    v-model.trim="Navigation.Url">
                     <span v-show="Submitted && !$v.Navigation.Url.required" class="text-danger"> Field harus diisi </span>
                  </div>
                  <div class="form-group">
                    <label for="Url"><strong> Group </strong></label>
                    <select v-model.trim="Navigation.GroupId"
                              class="form-control col-lg-4">
                              <option v-for="i in Group" v-bind:value="i.GroupId">{{i.GroupName}}</option>
                    </select>
                    <span v-show="Submitted && !$v.Navigation.GroupId.required" class="text-danger"> Field harus diisi </span>
                  </div>
                  <div class="form-group">
                    <label for="Icon"><strong> Icon </strong></label>
                    <input type="text" class="form-control col-lg-4" id="Icon" placeholder="Icon"
                    v-model.trim="Navigation.Icon">
                     <span v-show="Submitted && !$v.Navigation.Icon.required" class="text-danger"> Field harus diisi </span>
                  </div>
                  <div class="form-group">
                    <label for="Order"><strong> Order </strong></label>
                    <input type="text" class="form-control col-lg-4" id="Icon" placeholder="Order"
                    v-model.trim="Navigation.Order">
                     <span v-show="Submitted && !$v.Navigation.Order.required" class="text-danger"> Field harus diisi </span>
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
  <script src="../../js/App/NavigationSetting.js"></script>
