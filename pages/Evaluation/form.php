<?php
$SERVER = $_SERVER['DOCUMENT_ROOT'];
include $SERVER."/Evaluation/Config/ConfigHead.php";
?>
<div class="main-panel" id="evaluation">
  <form class="forms-sample" action="PR.php" method="POST">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-md-6 d-flex align-items-stretch grid-margin">
          <div class="row flex-grow">
          </div>
        </div>
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <h4 class="text-center"><strong>Evaluasi Penawaran</strong></h4>
                </div>
              </div>
              <div class="row">
                <div class="form-group row col-md-12">
                  <label for="PRName" class="col-sm-3 col-form-label"><strong> No Evaluasi Penawaran </strong></label>
                  <div class="col-sm-7">
                    <label class="col-form-label"> - </label>
                  </div>
                </div>
                <div class="form-group row col-md-12">
                  <label for="PRName" class="col-sm-3 col-form-label"><strong> Purchasing Code </strong></label>
                  <div class="col-sm-7">
                    <label class="col-form-label"> - </label>
                  </div>
                </div>
                <div class="form-group row col-md-12">
                  <label for="Project" class="col-sm-3 col-form-label"><strong>RFR. / PR. No.</strong></label>
                  <div class="col-sm-7">
                    <label class="col-form-label">{{Evaluation.PR.PRCode}}</label>
                  </div>
                </div>
                <div class="form-group row col-md-12">
                  <label for="Project" class="col-sm-3 col-form-label"><strong>Collective No.</strong></label>
                  <div class="col-sm-7">
                    <label class="col-form-label"> - </label>
                  </div>
                </div>
                <div class="form-group row col-md-12">
                  <label for="Project" class="col-sm-3 col-form-label"><strong>Cost Center / Assets No. / Order No.</strong></label>
                  <div class="col-sm-7">
                    <label class="col-form-label"> DRILLING EMR-01 </label>
                  </div>
                </div>
                <div class="form-group row col-md-12">
                  <label for="Project" class="col-sm-3 col-form-label"><strong>Project</strong></label>
                  <div class="col-sm-7">
                    <label class="col-form-label"> {{Evaluation.PR.Project}} </label>
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
                <div class="panel">
                  <div></div>

                </div>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <td rowspan="4"><strong>NO</strong></td>
                        <td rowspan="4"><strong>QTY</strong></td>
                        <td rowspan="4"><strong>UoM</strong></td>
                        <td rowspan="4"><strong>NAMA BARANG/JASA</strong></td>
                      </tr>
                      <tr>
                        <td v-for="(vendor,index) in PRVendors" class="text-center" colspan="4">
                          <strong>Penawaran {{index+ 1}} </strong>
                        </td>
                      </tr>
                      <tr>
                        <td v-for="(vendor,index) in PRVendors" class="text-center" colspan="4">
                          <strong> {{vendor.VendorName}} </strong>
                        </td>
                      </tr>
                      <tr>
                        <template v-for="(vendor,index) in PRVendors">
                        <td><strong>DELIVERY</strong></td>
                        <td><strong>UNIT PRICE</strong></td>
                        <td style="min-width: 150px !important;"><strong>TOTAL</strong></td>
                        <td><strong>DESCRIPTION</strong></td>
                        </template>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item , index) in ItemEvaluation">
                        <td>{{index + 1}}</td>
                        <td>{{item.Qty}}</td>
                        <td>{{item.UoMName}}</td>
                        <td>{{item.ItemName}}</td>
                        <template  v-for="(vendor,index) in item.Vendor">
                        <td>
                          <input type="date" class="form-control" v-model="vendor.Delivery"/>
                          <span v-show="Submitted && IsValueNull(vendor.Delivery)" class="text-danger"> Field harus diisi </span>
                        </td>
                        <td>
                          <input type="number" class="form-control" v-model="vendor.UnitPrice" v-on:change="CalculateTotal()"/>
                          <span v-show="Submitted && IsValueNull(vendor.UnitPrice)" class="text-danger"> Field harus diisi </span>
                        </td>
                        <td>
                          <input type="text" class="form-control" v-model="vendor.TotalPrice"
                          disabled="disabled" v-on:change="CalculateTotal()"/>
                        </td>
                        <td>
                          <input type="text" class="form-control" v-model="vendor.Description"/>
                        </td>
                        </template>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>
                          <strong>INCLUDE</strong>
                        </td>
                        <template  v-for="(vendor,index) in PRVendors">
                        <td colspan="4" style="padding: 0px !important;vertical-align: top !important;">
                          <button type="button" class="btn btn-icons btn-rounded btn-outline-primary"
                          v-on:click="AddInclude(index)">
                          <i class="mdi mdi-plus"></i>
                          </button>
                          <ol>
                            <li v-for="(include,index) in vendor.Include">
                              <div class="form-group row col-md-12">
                                <div class="col-sm-10">
                                  <input type="text" v-model="include.ItemName" class="form-control">
                                   <span v-show="Submitted && IsValueNull(include.ItemName)" class="text-danger"> Field harus diisi </span>
                                </div>
                                <label class="col-sm-2">
                                  <button type="button" class="btn btn-icons btn-rounded btn-danger"
                                  v-on:click="DeleteInclude(vendor.Include,index)">
                                    <i class="mdi mdi-close"></i>
                                  </button>
                                </label>
                              </div>
                            </li>
                          </ol>
                        </td>
                        </template>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>
                          <strong>EXCLUDE</strong>
                        </td>
                        <template  v-for="(vendor,index) in PRVendors">
                        <td colspan="4" style="padding: 0px !important; vertical-align: top !important;">
                          <button type="button" class="btn btn-icons btn-rounded btn-outline-primary"
                           v-on:click="AddExclude(index)">
                          <i class="mdi mdi-plus"></i>
                          </button>
                          <ol>
                            <li v-for="(include,index) in vendor.Exclude">
                              <div class="form-group row col-md-12">
                                <div class="col-sm-10 ">
                                  <input type="text"v-model="include.ItemName" class="form-control">
                                   <span v-show="Submitted && IsValueNull(include.ItemName)" class="text-danger"> Field harus diisi </span>
                                </div>
                                <label class="col-sm-2">
                                  <button type="button" class="btn btn-icons btn-rounded btn-danger"
                                  v-on:click="DeleteExclude(vendor.Exclude,index)">
                                    <i class="mdi mdi-close"></i>
                                  </button>
                                </label>
                              </div>
                            </li>
                          </ol>
                        </td>
                        </template>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td><strong>SUB TOTAL</strong></td>
                        <template  v-for="(vendor,index) in PRVendors">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                          {{vendor.EvaluationDetail.SubTotal}}
                        </td>
                        </template>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td><strong>DISCOUNT</strong></td>
                        <template  v-for="(vendor,index) in PRVendors">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                          <div class="form-group">
                            <div class="input-group">
                              <input type="number" class="form-control" v-model="vendor.EvaluationDetail.Discount" v-on:change="CalculateTotal()">
                              <div class="input-group-prepend">
                                <span class="input-group-text">%</span>
                              </div>
                            </div>
                          </div>
                        </td>
                        </template>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td><strong>TOTAL</strong></td>
                        <template  v-for="(vendor,index) in PRVendors">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                          {{vendor.EvaluationDetail.Total}}
                        </td>
                        </template>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td ><strong> PPN 10% </strong></td>
                        <template  v-for="(vendor,index) in PRVendors">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                          {{vendor.EvaluationDetail.PPN}}
                        </td>
                        </template>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td ><strong> GRAND TOTAL </strong></td>
                        <template  v-for="(vendor,index) in PRVendors">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                          {{vendor.EvaluationDetail.GrandTotal}}
                        </td>
                        </template>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td><strong> DELIVERY POINT </strong></td>
                        <template  v-for="(vendor,index) in PRVendors">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                          <input type="text" name="" class="form-control" v-model="vendor.EvaluationDetail.DeliveryPoint">
                           <span v-show="Submitted && IsValueNull(vendor.EvaluationDetail.DeliveryPoint)" class="text-danger"> Field harus diisi </span>
                        </td>
                        </template>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td><strong> PAYMENT TERMS </strong></td>
                        <template  v-for="(vendor,index) in PRVendors">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                          <input type="number" name="" class="form-control" v-model="vendor.EvaluationDetail.PaymentTerms">
                          <span v-show="Submitted && IsValueNull(vendor.EvaluationDetail.PaymentTerms)" class="text-danger"> Field harus diisi </span>
                        </td>
                        </template>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td><strong> PRICE IDR </strong></td>
                        <template  v-for="(vendor,index) in PRVendors">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                          <select v-model="vendor.EvaluationDetail.IDRId" class="form-control">
                            <option v-for="i in IDR" v-bind:value="i.IDRId">{{i.IDRCode}}</option>
                          </select>
                            <span v-show="Submitted && IsValueNull(vendor.EvaluationDetail.IDRId)" class="text-danger"> Field harus diisi </span>
                        </td>
                        </template>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="col-md-12" style="padding-top: 30px">
                  <label class="col-form-label"><strong>NOTE</strong></label>
                </div>
                <div class="col-md-6">
                  <textarea class="form-control" rows="6" v-model="Evaluation.Note">

                  </textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row text-right">
        <div class="col-md-12">
          <input type="button" class="btn btn-lg btn-success" value="Submit" v-on:click="Submit()"/>
          <input type="button" value="Cancel"  class="btn  btn-lg btn-default" onclick="goBack()">
        </div>
      </div>
    </div>
  </form>
<?php include $SERVER."/Evaluation/Config/ConfigFooter.php"; ?>
<script src="../../js/App/Evaluation.js"></script>
