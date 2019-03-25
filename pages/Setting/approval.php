<?php
$SERVER = $_SERVER['DOCUMENT_ROOT'];
include $SERVER."/Evaluation/Config/ConfigHead.php";
?>
<div class="main-panel" id="item">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h2 class="card-title"><strong>Daftar Item</strong></h2>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>
                      <strong> No </strong>
                    </th>
                    <th>
                      <strong> Nama Item </strong>
                    </th>
                    <th>
                      <strong> Deskripsi </strong>
                    </th>
                    <th>
                      <strong>Action</strong>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item,index) in Items">
                    <td>{{index + 1}}</td>
                    <td>{{item.ItemName}}</td>
                    <td>{{item.Description}}</td>
                    <td><button v-on:click="Edit(item.ItemId)" class="btn btn-primary mr-2">Edit</button></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="row">
              <button class="btn btn-success mr-2" style="margin-top: 30px;" v-on:click="NewItem">Tambah Item</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- The Modal -->
  <div class="modal fade" id="ItemModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Form Tambah Item Baru</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <form class="forms-sample">
                  <div class="form-group">
                    <label for="ItemName"><strong> Nama Item </strong></label>
                    <input type="text" class="form-control col-lg-4" id="ItemName" placeholder="Nama Item"
                    v-model.trim="Item.ItemName">
                     <span v-show="Submitted && !$v.Item.ItemName.required" class="text-danger"> Field harus diisi </span>
                  </div>
                  <div class="form-group">
                    <label for="Deskription"><strong> Deskripsi </strong></label>
                    <textarea class="form-control col-lg-4"
                    id="Deskription" placeholder="Deskripsi"
                    v-model.trim="Item.Description" rows="4"></textarea>
                  </div>
                  <div class="form-group" v-show="Item.ItemId">
                    <label for="Status"><strong>Status</strong></label>
                    <div class="form-radio">
                      <label class="form-check-label">
                        <input type="radio"
                        class="form-check-input"
                        v-model.trim="Item.Status" id="Status"
                        value="Active" checked> Active
                      </label>
                    </div>
                    <div class="form-radio">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input"
                        v-model.trim="Item.Status"
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
  <script src="../../js/App/Item.js"></script>
