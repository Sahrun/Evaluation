<?php
$SERVER = $_SERVER['DOCUMENT_ROOT'];
include $SERVER."/Evaluation/Config/ConfigHead.php";
?>
<div class="main-panel" id="evaluation">
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
                <h4 class="text-center"><strong>Detail Evaluasi Penawaran</strong></h4>
              </div>
            </div>
            <div class="row">
              <div class="form-group row col-md-12">
                <label for="PRName" class="col-sm-3 col-form-label"><strong> No Evaluasi Penawaran </strong></label>
                <div class="col-sm-7">
                  <label class="col-form-label"> {{Evaluation.EvaluationCode}} </label>
                </div>
              </div>
              <div class="form-group row col-md-12">
                <label for="PRName" class="col-sm-3 col-form-label"><strong> Purchasing Code </strong></label>
                <div class="col-sm-7">
                  <label class="col-form-label"><strong> - / - </strong></label>
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
                  <label class="col-form-label"><strong> - / - </strong></label>
                </div>
              </div>
              <div class="form-group row col-md-12">
                <label for="Project" class="col-sm-3 col-form-label"><strong>Cost Center / Assets No. / Order No.</strong></label>
                <div class="col-sm-7">
                  <label class="col-form-label"><strong> - / - </strong></label>
                </div>
              </div>
              <div class="form-group row col-md-12">
                <label for="Project" class="col-sm-3 col-form-label"><strong>Project</strong></label>
                <div class="col-sm-7">
                  <label class="col-form-label"> {{Evaluation.PR.Project}}
                  </label>
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
            </div>
            <div class="row">
              <div class="panel">
                <div></div>

              </div>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <td rowspan="5"><strong>NO</strong></td>
                      <td rowspan="5"><strong>QTY</strong></td>
                      <td rowspan="5"><strong>UoM</strong></td>
                      <td rowspan="5"><strong>NAMA BARANG/JASA</strong></td>
                    </tr>
                    <tr>
                      <td v-for="(vendor,index) in Evaluation.PRVendor" class="text-center" colspan="5">
                        <strong>Penawaran {{index+ 1}} </strong>
                      </td>
                    </tr>
                    <tr>
                      <td v-for="(vendor,index) in Evaluation.PRVendor" class="text-center" colspan="5">
                        <strong> {{vendor.VendorName}} </strong>
                      </td>
                    </tr>
                    <tr>
                      <template v-for="(vendor,index) in Evaluation.PRVendor">
                      <td>Review</td>
                      <td><strong>DELIVERY</strong></td>
                      <td><strong>UNIT PRICE</strong></td>
                      <td style="min-width: 150px !important;"><strong>TOTAL</strong></td>
                      <td><strong>DESCRIPTION</strong></td>
                      </template>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item , index) in Evaluation.Evaluationdetailvendor">
                      <td>{{index + 1}}</td>
                      <td>{{item.Qty}}</td>
                      <td>{{item.UoMName}}</td>
                      <td>{{item.ItemName}}</td>
                      <template  v-for="(vendor,index) in item.Vendor">
                      <td>
                        <div>
                        <ul>
                        <li v-for="(review , i) in vendor.Reviews" style="color:blue">{{review.FullName}}</li>
                        </ul>
                        </div>
                      </td>
                      <td>{{vendor.Delivery}}</td>
                      <td>{{vendor.UnitPrice}}</td>
                      <td>{{vendor.TotalPrice}} </td>
                      <td>{{vendor.Description}}</td>
                      </template>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                      <td>
                        <strong>INCLUDE</strong>
                      </td>
                      <template  v-for="(vendor,index) in Evaluation.PRVendor">
                      <td colspan="5" style="padding: 0px !important;vertical-align: top !important;">
                        <table class="table table-bordered">
                          <tr v-for="(include,index) in vendor.Include">
                            <td>
                              <span v-show="include.ItemName">{{index+1}} . {{include.ItemName}}
                              </span>
                              <span v-show="!include.ItemName"> - </span>
                            </td>
                          </tr>
                        </table>
                      </td>
                      </template>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                      <td>
                        <strong>EXCLUDE</strong>
                      </td>
                      <template  v-for="(vendor,index) in Evaluation.PRVendor">
                      <td colspan="5" style="padding: 0px !important; vertical-align: top !important;">
                        <table class="table table-bordered">
                          <tr v-for="(include,index) in vendor.Exclude">
                            <td>
                              <span v-show="include.ItemName"> {{index+1}} . {{include.ItemName}}</span>
                              <span v-show="!include.ItemName"> - </span>
                            </td>
                          </tr>
                        </table>
                      </td>
                      </template>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                      <td><strong>SUB TOTAL</strong></td>
                      <template  v-for="(vendor,index) in Evaluation.PRVendor">
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>
                        {{vendor.EvaluationDetail.SubTotal}}
                      </td>
                      <td></td>
                      </template>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                      <td><strong>DISCOUNT</strong></td>
                      <template  v-for="(vendor,index) in Evaluation.PRVendor">
                      <td></td>
                      <td></td>
                      <td></td>
                      <td> <span v-show="vendor.EvaluationDetail.Discount">{{vendor.EvaluationDetail.Discount}}%</span></td>
                      <td></td>
                      </template>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                      <td><strong>TOTAL</strong></td>
                      <template  v-for="(vendor,index) in Evaluation.PRVendor">
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>{{vendor.EvaluationDetail.Total}}</td>
                      <td></td>
                      </template>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                      <td ><strong> PPN 10% </strong></td>
                      <template  v-for="(vendor,index) in Evaluation.PRVendor">
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>{{vendor.EvaluationDetail.PPN}}</td>
                      <td></td>
                      </template>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                      <td ><strong> GRAND TOTAL </strong></td>
                      <template  v-for="(vendor,index) in Evaluation.PRVendor">
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>{{vendor.EvaluationDetail.GrandTotal}}</td>
                      <td></td>
                      </template>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                      <td><strong> DELIVERY POINT </strong></td>
                      <template  v-for="(vendor,index) in Evaluation.PRVendor">
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>{{vendor.EvaluationDetail.DeliveryPoint}}</td>
                      <td></td>
                      </template>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                      <td><strong> PAYMENT TERMS </strong></td>
                      <template  v-for="(vendor,index) in Evaluation.PRVendor">
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>{{vendor.EvaluationDetail.PaymentTerms}}</td>
                      <td></td>
                      </template>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                      <td><strong> PRICE IDR </strong></td>
                      <template  v-for="(vendor,index) in Evaluation.PRVendor">
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>{{vendor.EvaluationDetail.IDRCode}}</td>
                      <td></td>
                      </template>
                    </tr>
                    <tr>
                      <td colspan="3" class="text-center"> <strong> REKOMENDASI </strong></td>
                      <td colspan="100"></td>
                    </tr>
                    <tr>
                      <td colspan="100" class="text-left">
                        <strong> NOTE </strong> : <br>
                        {{Evaluation.Note}}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th rowspan="3" style="min-width: 200px;">
                        <template v-for="(approval, index) in Evaluation.Approval">
                        <div v-show="index == 0">
                          <div class="col-md-12 text-center">
                            <span><strong>MENGETAHUI,</strong></span>
                          </div>
                          <div class="col-md-12 text-center" style="padding-top: 50px;">
                            <span v-show="approval.IsApprove == 0">MENUNGGU</span>
                            <span v-show="approval.IsApprove == 1">
                             SELESAI
                            </span>
                          </div>
                          <div class="col-md-12 text-center" style="padding-top: 50px;">
                            <span><strong>{{approval.FullName}}</strong></span> <br>
                            <span><strong>{{approval.Department}}</strong></span>
                          </div>
                        </div>
                        </template>
                      </th>
                    </tr>
                    <tr>
                      <td :colspan="(Evaluation.Approval.length - 1)" class="text-center"><strong>EVALUATOR / MENYETUJUI</strong></td>
                      <td rowspan="3" style="min-width: 200px;">
                        <div class="col-md-12 text-center">
                          <span><strong>DIBUAT OLEH,</strong></span>
                        </div>
                        <div class="col-md-12 text-center" style="padding-top: 50px;">

                        </div>
                        <div class="col-md-12 text-center" style="padding-top: 120px;">
                          <span><strong>{{Evaluation.User.FullName}}</strong></span> <br>
                          <span><strong>{{Evaluation.User.Department}}</strong></span>
                        </div>
                      </td>
                    </tr>
                    <tr> 
                      <td>
                        <template v-for="(approval, index) in Evaluation.Approval">
                        <div v-show="index != 0">
                          <div class="col-md-12 text-center" style="padding-top: 50px;">
                            <span v-show="approval.IsApprove == 0">MENUNGGU</span>
                            <span v-show="approval.IsApprove == 1">
                              SELESAI
                            </span>
                          </div>
                          <div class="col-md-12 text-center" style="padding-top: 80px;">
                            <span><strong>{{approval.FullName}}</strong></span> <br>
                            <span><strong>{{approval.Department}}</strong></span>
                          </div>
                        </div>
                        </template>
                      </td>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row text-right">
      <div class="col-md-12">
        <input type="button" value="Print"  class="btn  btn-lg btn-success" v-on:click="Print">
        <input type="button" value="Cancel"  class="btn  btn-lg btn-default" onclick="goBack()">
      </div>
    </div>
  </div>
<?php include $SERVER."/Evaluation/Config/ConfigFooter.php"; ?>
  <script src="../../js/App/Evaluation.js"></script>
