<?php
$SERVER = $_SERVER['DOCUMENT_ROOT'];
include $SERVER."/Evaluation/Config/ConfigHead.php";
?>
<div class="main-panel" id="IDR">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h2 class="card-title"><strong>Daftar IDR</strong></h2>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>
                      <strong> No </strong>
                    </th>
                    <th>
                      <strong> Kode IDR </strong>
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
                  <tr v-for="(idr,index) in IDRs">
                    <td>{{index + 1}}</td>
                    <td>{{idr.IDRCode}}</td>
                    <td>{{idr.Description}}</td>
                    <td><button v-on:click="Edit(idr.IDRId)" class="btn btn-primary mr-2">Edit</button></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="row">
              <button class="btn btn-success mr-2" style="margin-top: 30px;" v-on:click="NewIDR">Tambah IDR</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- The Modal -->
  <div class="modal fade" id="IDRModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Form Tambah IDR Baru</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <form class="forms-sample">
                  <div class="form-group">
                    <label for="IDRCode"><strong> Kode IDR </strong></label>
                    <input type="text" class="form-control col-lg-4" id="KodeIDR" placeholder="Kode IDR"
                    v-model.trim="IDR.IDRCode">
                     <span v-show="Submitted && !$v.IDR.IDRCode.required" class="text-danger"> Field harus diisi </span>
                  </div>
                  <div class="form-group">
                    <label for="Description"><strong> Description </strong></label>
                    <textarea class="form-control col-lg-4"
                    id="Description"
                    placeholder="Description"
                    v-model.trim="IDR.Description"
                    rows="4">
                    </textarea>
                  </div>
                  <div class="form-group" v-show="IDR.IDRId">
                    <label for="Status"><strong>Status</strong></label>
                    <div class="form-radio">
                      <label class="form-check-label">
                        <input type="radio"
                        class="form-check-input"
                        v-model.trim="IDR.Status" id="Status"
                        value="Active" checked> Active
                      </label>
                    </div>
                    <div class="form-radio">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input"
                        v-model.trim="IDR.Status"
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
  <script src="../../js/App/IDR.js"></script>