<?php
$SERVER = $_SERVER['DOCUMENT_ROOT'];
include $SERVER."/Evaluation/Config/ConfigHead.php";
?>
<div class="main-panel" id="pr">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h2 class="card-title"><strong>Daftar Purchase Requesition </strong></h2>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>
                      <strong> No </strong>
                    </th>
                    <th>
                      <strong> Code PR </strong>
                    </th>
                    <th>
                      <strong> Nama PR </strong>
                    </th>
                    <th>
                      <strong>Project</strong>
                    </th>
                    <th>
                      <strong>Evaluasi</strong>
                    </th>
                    <th>
                      <strong>Status Evaluasi</strong>
                    </th>
                    <th>
                      <strong>Action</strong>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(pr , index) in PRs">
                    <td>{{index + 1}}</td>
                    <td>{{pr.PRCode}}</td>
                    <td>{{pr.PRName}}</td>
                    <td>{{pr.Project}}</td>
                    <td>{{pr.IsEvaluation == 0? 'BELUM':'SUDAH' }}</td>
                    <td>{{pr.EvalApprove}}</td>
                    <td>
                      <button class="btn btn-primary mr-2" v-on:click="View(pr.PRId)">View</button>
                      <a :href="'<?php echo $_ROOT ?>pages/Evaluation/form.php?key='+pr.PRId" class="btn btn-warning mr-2"
                      v-show="pr.IsEvaluation == 0">Buat Evaluasi</a>
                      <a :href="'<?php echo $_ROOT ?>pages/Evaluation/view.php?view='+pr.EvaluationId" class="btn btn-info mr-2"
                      v-show="pr.IsEvaluation == 1">View Evaluasi</a> 
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="row">
              <button class="btn btn-success mr-2" style="margin-top: 30px;" v-on:click="NewPR"> Tambah PR </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="PRModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title"><strong> Form Tambah PR Baru</strong> </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-9">
                    <h4><strong>PR</strong></h4>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group row col-md-12">
                    <label for="PRName" class="col-sm-2 col-form-label"><strong> Nama PR </strong></label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="PRName" name="PRName" placeholder="Nama PR"
                      v-model.trim="PR.PRName">
                      <span v-show="Submitted && !$v.PR.PRName.required" class="text-danger"> Field harus diisi </span>
                    </div>
                  </div>
                  <div class="form-group row col-md-12">
                    <label for="Project" class="col-sm-2 col-form-label"><strong>Nama Project</strong></label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="Project" name="Project" placeholder="Nama Project"
                      v-model.trim="PR.Project">
                      <span v-show="Submitted && !$v.PR.Project.required" class="text-danger"> Field harus diisi </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-9">
                    <h4><strong>Item</strong></h4>
                  </div>
                </div>
                <div class="row">
                  <div class="panel">
                    <div></div>
                    
                  </div>
                  <div class="table-responsive">
                    <table class="table table" id="tableItem">
                      <thead>
                        <tr>
                          <th>
                            <strong> No </strong>
                          </th>
                          <th>
                            <strong> Nama Item </strong>
                          </th>
                          <th>
                            <strong>Qty</strong>
                          </th>
                          <th>
                            <strong>UoM</strong>
                          </th>
                          <th>
                            <strong>Action</strong>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(prItem, index) in $v.PRItem.$each.$iter">
                          <td>{{parseInt(index) + 1}}</td>
                          <td>
                            <select v-model.trim="prItem.ItemId.$model"
                              class="form-control">
                              <option v-for="i in Items" v-bind:value="i.ItemId">{{i.ItemName}}</option>
                            </select>
                            <span v-show="Submitted && !prItem.ItemId.required" class="text-danger"> Field harus diisi </span>
                          </td>
                          <td>
                            <input type="number" v-model.trim="prItem.Qty.$model" class="form-control">
                            <span v-show="Submitted && !prItem.Qty.required" class="text-danger"> Field harus diisi </span>
                            <span v-show="Submitted && !prItem.Qty.between" class="text-danger"> Harus lebih dari 0 dan kurang dari 100 </span>
                          </td>
                          <td>
                            <select v-model.trim="prItem.UoMId.$model"
                              class="form-control">
                              <option v-for="i in UoMs" v-bind:value="i.UoMId">{{i.UoMName}}</option>
                            </select>
                            <span v-show="Submitted && !prItem.UoMId.required" class="text-danger"> Field harus diisi </span>
                          </td>
                          <td>
                            <span class="btn btn-danger btn-sm" v-on:click="DeleteItem(index)">
                              <i class="fa fa-times"></i>
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <span v-show="Submitted && !$v.PRItem.required" class="text-danger"> Minimal Ada Satu Item </span>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <input type="button"
                    name=""
                    value="+ Item"
                    class="btn btn-primary"
                    v-on:click="NewPritem"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h2 class="card-title"><strong>vendor</strong></h2>
                <div class="row">
                  <div class="panel">
                    <div></div>
                    
                  </div>
                  <div class="table-responsive">
                    <table class="table table" id="tableVendor">
                      <thead>
                        <tr>
                          <th>
                            <strong> No </strong>
                          </th>
                          <th>
                            <strong> Nama Vendor </strong>
                          </th>
                          <th>
                            <strong>Alamat</strong>
                          </th>
                          <th>
                            <strong>Action</strong>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(prVendor, index) in $v.PRVendor.$each.$iter">
                          <td>{{parseInt(index) + 1}}</td>
                          <td>
                            <select v-model.trim="prVendor.VendorId.$model"
                              class="form-control"
                              v-on:change="SetVendor(prVendor.VendorId.$model,index)">
                              <option v-for="i in Vendors" v-bind:value="i.VendorId">{{i.VendorName}}</option>
                            </select>
                            <span v-show="Submitted && !prVendor.VendorId.required" class="text-danger"> Field harus diisi </span>
                          </td>
                          <td>{{prVendor.$model.Address}}</td>
                          <td>
                            <span class="btn btn-danger btn-sm" v-on:click="DeleteVendor(index)">
                              <i class="fa fa-times"></i>
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <span v-show="Submitted && !$v.PRVendor.required" class="text-danger"> Minimal Ada Satu Vendor </span>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <input type="button" value="+ Vendor" class="btn btn-primary"  v-on:click="NewVendor"/>
                  </div>
                </div>
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
  <div class="modal fade" id="PRModalView" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title"><strong> View PR</strong> </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-9">
                    <h4><strong>PR</strong></h4>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group row col-md-12">
                    <label for="PRName" class="col-sm-2 col-form-label"><strong> Nama PR </strong></label>
                    <div class="col-sm-8">
                      <label class="col-form-label">{{PRView.PRName}}</label>
                    </div>
                  </div>
                  <div class="form-group row col-md-12">
                    <label for="Project" class="col-sm-2 col-form-label"><strong>Nama Project</strong></label>
                    <div class="col-sm-8">
                      <label class="col-form-label">{{PRView.Project}}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-9">
                    <h4><strong>Item</strong></h4>
                  </div>
                </div>
                <div class="row">
                  <div class="panel">
                    <div></div>
                    
                  </div>
                  <div class="table-responsive">
                    <table class="table table" id="tableItem">
                      <thead>
                        <tr>
                          <th>
                            <strong> No </strong>
                          </th>
                          <th>
                            <strong> Nama Item </strong>
                          </th>
                          <th>
                            <strong>Qty</strong>
                          </th>
                          <th>
                            <strong>UoM</strong>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(prItem, index) in PRView.PRItem">
                          <td>{{index + 1}}</td>
                          <td>
                            {{prItem.ItemName}}
                          </td>
                          <td>
                            {{prItem.Qty}}
                          </td>
                          <td>
                            {{prItem.UoMName}}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h2 class="card-title"><strong>vendor</strong></h2>
                <div class="row">
                  <div class="panel">
                    <div></div>
                    
                  </div>
                  <div class="table-responsive">
                    <table class="table table" id="tableVendor">
                      <thead>
                        <tr>
                          <th>
                            <strong> No </strong>
                          </th>
                          <th>
                            <strong> Nama Vendor </strong>
                          </th>
                          <th>
                            <strong>Alamat</strong>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(prVendor, index) in PRView.PRVendor">
                          <td>{{index + 1}}</td>
                          <td>
                            {{prVendor.VendorName}}
                          </td>
                          <td>
                            {{prVendor.Address}}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <?php include $SERVER."/Evaluation/Config/ConfigFooter.php"; ?>
  <script src="../../js/App/PR.js"></script>